<?php

namespace App\Http\Controllers;

use App\Services\CSVService;
use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreStudentRequest;

class StudentController extends Controller
{
    private CSVService $csvService;

    public function __construct(CSVService $csvService)
    {
        $this->csvService = $csvService;
    }

    public function importCSV(StoreStudentRequest $request)
    {
        try {
            $filePath = $request->file('file')->getRealPath();
            $this->csvService->importStudentsFromCSV($filePath);

            return ApiResponseClass::sendResponse(null, 'Importación exitosa', 200);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, ' ' . $e->getMessage());
        }
    }

    public function exportCSV()
    {
        try {
            $filePath = $this->csvService->exportStudentsToCSV();
            return response()->download($filePath, 'students.csv');
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Error en la exportación: ' . $e->getMessage());
        }
    }
}
