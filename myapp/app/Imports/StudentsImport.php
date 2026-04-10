<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentsImport
{
    public function __construct(protected Classroom $classroom) {}

    public function import(string $filePath): int
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \Exception("File not found or not readable: {$filePath}");
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        $imported = 0;

        // Skip row 0 (headers) and row 1 (notes) — data starts at index 2
        foreach (array_slice($rows, 2) as $row) {
            [$studentId, $lastName, $firstName, $mi,
             $course, $year, $block, $lab, $lec] = array_pad(array_values($row), 9, null);

            // Skip empty or example rows
            if (empty(trim((string)$lastName)) || empty(trim((string)$firstName))) continue;
            if (str_contains(strtolower((string)$lastName), 'dela cruz')) continue;
            if (str_contains(strtolower((string)$studentId), 'e.g')) continue;

            Student::create([
                'classroom_id'     => $this->classroom->id,
                'student_id'       => trim((string)($studentId ?? '')),
                'last_name'        => trim((string)$lastName),
                'first_name'       => trim((string)$firstName),
                'middle_initial'   => !empty($mi) ? trim((string)$mi) : null,
                'course'           => trim((string)($course ?? '')),
                'year'             => trim((string)($year ?? '')),
                'block'            => trim((string)($block ?? '')),
                'laboratory_grade' => is_numeric($lab) ? (float)$lab : null,
                'lecture_grade'    => is_numeric($lec) ? (float)$lec : null,
            ]);

            $imported++;
        }

        return $imported;
    }
}