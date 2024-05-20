<?php

namespace App\Repositories;

use App\Models\Student;
use App\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{
    public function exportStudentsToCSV()
    {
        return Student::all();
    }

    public function importStudentsFromCSV(array $data)
    {
        return Student::create($data);
    }
}
