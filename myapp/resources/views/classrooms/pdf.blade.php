<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $classroom->course_code }} Students</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1,
        h2 {
            margin: 0 0 8px 0;
        }

        .header {
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f7f7f7;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $classroom->course_name }}</h1>
        <h2>{{ $classroom->course_code }} | Year {{ $classroom->year }} | Block {{ $classroom->block }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%">Student ID</th>
                <th style="width: 28%">Name</th>
                <th style="width: 20%">Course / Year / Block</th>
                <th style="width: 12%" class="text-center">Lab Grade</th>
                <th style="width: 12%" class="text-center">Lec Grade</th>
                <th style="width: 16%" class="text-center">Average</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->course }} / {{ $student->year }} / {{ $student->block }}</td>
                    <td class="text-center">{{ $student->laboratory_grade ?? '—' }}</td>
                    <td class="text-center">{{ $student->lecture_grade ?? '—' }}</td>
                    <td class="text-center">
                        {{ $student->average_grade !== null ? number_format($student->average_grade, 2) : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>