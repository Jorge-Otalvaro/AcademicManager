<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Interfaces\StudentRepositoryInterface;

class CSVService
{
    private StudentRepositoryInterface $studentRepositoryInterface;

    public function __construct(StudentRepositoryInterface $studentRepositoryInterface)
    {
        $this->studentRepositoryInterface = $studentRepositoryInterface;
    }

    public function importStudentsFromCSV($filePath)
    {
        $file = fopen($filePath, 'r');

        // Validar que el archivo CSV tenga el formato correcto
        $header = fgetcsv($file);
        if ($header === false || count($header) != 6 || $header !== ['Identification', 'Name', 'Age', 'Grade', 'Subject', 'GradeValue']) {
            throw new \Exception('El formato del archivo CSV no es válido');
        }

        $header = array_map('trim', $header);

        DB::beginTransaction();

        try {
            // Iterar sobre las filas del archivo CSV
            while (($row = fgetcsv($file)) !== false) {
                
                $row = array_map('trim', $row);

                // Validar los datos de la fila                
                $validator = Validator::make($row, [
                    'Identification' => 'required|string|unique:students,identification',
                    'Name' => 'required|string',
                    'Age' => 'required|integer',
                    'Grade' => 'required|string',
                    'Subject' => 'required|string',
                    'GradeValue' => 'required|numeric'
                ]);

                // Lanzar una excepción si hay errores de validación
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // Crear o actualizar el estudiante en la base de datos
                $student = $this->studentRepositoryInterface->storeStudentsFromCSV($row);

                // Obtener o crear la materia en la base de datos
                $this->studentRepositoryInterface->storeGradesFromCSV($student->id, $row);

                // Asociar la materia al estudiante si no está asociada ya
                if (!$student->grades()->where('subject', $row[4])->exists()) {
                    $student->grades()->create(['subject' => $row[4], 'grade' => $row[5]]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error en la importación: ' . $e->getMessage());
        } finally {
            fclose($file);
        }
    }

    public function exportStudentsToCSV()
    {
        $students = $this->studentRepositoryInterface->exportStudentsToCSV();

        // Crear un archivo temporal para almacenar los datos CSV
        $filePath = storage_path('app/students.csv');
        $file = fopen($filePath, 'w');

        // Escribir el encabezado del archivo CSV
        fputcsv($file, ['Identification', 'Name', 'Age', 'Grade', 'Subject', 'GradeValue']);

        // Escribir los datos de cada estudiante en el archivo CSV
        foreach ($students as $student) {
            foreach ($student->grades as $grade) {
                fputcsv($file, [
                    $student->identification,
                    $student->name,
                    $student->age,
                    $student->grade,
                    $grade->subject,
                    $grade->grade_value
                ]);
            }
        }

        fclose($file);

        return $filePath;
    }
}
