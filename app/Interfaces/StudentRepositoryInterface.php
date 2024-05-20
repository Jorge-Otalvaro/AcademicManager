<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function exportStudentsToCSV();

    public function storeStudentsFromCSV(array $data);

    public function storeGradesFromCSV(int $student, array $data);
}
