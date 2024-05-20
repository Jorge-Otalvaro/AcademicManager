<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function exportStudentsToCSV();
    public function importStudentsFromCSV(array $data);
}
