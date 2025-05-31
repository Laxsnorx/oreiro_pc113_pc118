<?php
namespace App\Exports;

use App\Models\Grade;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromCollection, WithHeadings
{
    public function collection()
    {

        $grades = Grade::with(['student', 'subject'])
            ->join('students', 'grades.student_id', '=', 'students.id')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
            ->orderBy('students.id', 'asc')
            ->orderBy('subjects.code', 'asc')
            ->select('grades.*')
            ->get();

        $grouped = $grades->groupBy('student_id');

        $result = collect();

        foreach ($grouped as $studentId => $gradesForStudent) {
            $totalWeightedGrade = 0;
            $totalUnits = 0;

            foreach ($gradesForStudent as $grade) {
                $units = $grade->subject->units ?? 0;
                $midterm = $grade->midterm_grade;
                $final = $grade->final_grade;

                $averageGrade = ($midterm + $final) / 2;

                $totalWeightedGrade += $averageGrade * $units;
                $totalUnits += $units;

                $result->push([
                    'Student Name'  => $grade->student->name ?? 'N/A',
                    'Student Email' => $grade->student->email ?? 'N/A',
                    'Subject Code'  => $grade->subject->code ?? 'N/A',
                    'Subject Title' => $grade->subject->description ?? 'N/A',
                    'Year Level'    => $grade->subject->year ?? 'N/A',
                    'Schedule'      => $grade->subject->schedule ?? 'N/A',
                    'Units'         => $units,
                    'Midterm Grade' => $midterm,
                    'Final Grade'   => $final,
                    'GWA'           => '', 
                ]);
            }

            $gwa = $totalUnits > 0 ? round($totalWeightedGrade / $totalUnits, 2) : 0;

            $result->push([
                'Student Name'  => $gradesForStudent[0]->student->name ?? 'N/A',
                'Student Email' => $gradesForStudent[0]->student->email ?? 'N/A',
                'Subject Code'  => '',
                'Subject Title' => '*** GWA ***',
                'Year Level'    => '',
                'Schedule'      => '',
                'Units'         => '',
                'Midterm Grade' => '',
                'Final Grade'   => '',
                'GWA'           => $gwa,
            ]);
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Student Email',
            'Subject Code',
            'Subject Title',
            'Year Level',
            'Schedule',
            'Units',
            'Midterm Grade',
            'Final Grade',
            'GWA',
        ];
    }
}