<?php

namespace App\Exports;

use App\Models\Classroom;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class StudentsExport
{
    public function __construct(protected Classroom $classroom) {}

    public function download(): void
    {
        $spreadsheet = $this->build(includeData: true);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $this->classroom->course_code . '_students.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function downloadTemplate(): void
    {
        $spreadsheet = $this->build(includeData: false);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="students_template.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    private function build(bool $includeData): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Students');

        // ── Headers ──────────────────────────────────────────
        $headers = [
            'A' => 'student_id',
            'B' => 'last_name',
            'C' => 'first_name',
            'D' => 'middle_initial',
            'E' => 'course',
            'F' => 'year',
            'G' => 'block',
            'H' => 'laboratory_grade',
            'I' => 'lecture_grade',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}1", $label);
        }

        // Header style — dark navy background, white bold text
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Notes row ─────────────────────────────────────────
        $notes = ['e.g. 2021-00123', 'e.g. Dela Cruz', 'e.g. Juan', 'e.g. R',
                  'e.g. BSIT', 'e.g. 3rd', 'e.g. A', '0 - 100', '0 - 100'];
        foreach ($notes as $i => $note) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}2", $note);
        }
        $sheet->getStyle('A2:I2')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['argb' => 'FF6B7280'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Data rows ─────────────────────────────────────────
        if ($includeData) {
            $row = 3;
            foreach ($this->classroom->students()->orderBy('last_name')->get() as $student) {
                $sheet->fromArray([
                    $student->student_id,
                    $student->last_name,
                    $student->first_name,
                    $student->middle_initial ?? '',
                    $student->course,
                    $student->year,
                    $student->block,
                    $student->laboratory_grade ?? '',
                    $student->lecture_grade ?? '',
                ], null, "A{$row}");

                // Zebra striping
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
                    ]);
                }
                $row++;
            }
        }

        // ── Column widths ─────────────────────────────────────
        $widths = ['A'=>18,'B'=>22,'C'=>22,'D'=>8,'E'=>12,'F'=>8,'G'=>8,'H'=>18,'I'=>16];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Freeze header row ─────────────────────────────────
        $sheet->freezePane('A3');

        return $spreadsheet;
    }
}