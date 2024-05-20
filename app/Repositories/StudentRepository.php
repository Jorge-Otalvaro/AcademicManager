<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\Grade;
use App\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{
    public function exportStudentsToCSV()
    {
        return Student::with(['grades'])->get();
    }

    public function storeStudentsFromCSV(array $data)
    {
        $student = Student::firstOrCreate(['identification' => $data[0]], [
            'name' => $data[1],
            'age' => $data[2],
            'grade' => $data[3]
        ]);

        return $student;
    }

    public function storeGradesFromCSV(int $student, array $data)
    {
        return Grade::firstOrCreate(['student_id' => $student, 'subject' => $data[4]], ['grade' => $data[5]]);
    }
}