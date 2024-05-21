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
            $rowNumber = 1;
            // Iterar sobre las filas del archivo CSV
            while (($row = fgetcsv($file)) !== false) {
                $rowNumber++;
                $row = array_map('trim', $row);

                // Validar los datos de la fila                
                $validator = Validator::make($row, [
                    0 => 'required|string',
                    1 => 'required|string',
                    2 => 'required|integer',
                    3 => 'required|string',
                    4 => 'required|string',
                    5 => 'required|numeric'
                ], [
                    '0.required' => 'El campo Identificación es requerido en la fila ' . $rowNumber . '.',
                    '0.string' => 'El campo Identificación debe ser una cadena de texto en la fila ' . $rowNumber . '.',
                    '1.required' => 'El campo Nombre es requerido en la fila ' . $rowNumber . '.',
                    '1.string' => 'El campo Nombre debe ser una cadena de texto en la fila ' . $rowNumber . '.',
                    '2.required' => 'El campo Edad es requerido en la fila ' . $rowNumber . '.',
                    '2.integer' => 'El campo Edad debe ser un número entero en la fila ' . $rowNumber . '.',
                    '3.required' => 'El campo Grado es requerido en la fila ' . $rowNumber . '.',
                    '3.string' => 'El campo Grado debe ser una cadena de texto en la fila ' . $rowNumber . '.',
                    '4.required' => 'El campo Asignatura es requerido en la fila ' . $rowNumber . '.',
                    '4.string' => 'El campo Asignatura debe ser una cadena de texto en la fila ' . $rowNumber . '.',
                    '5.required' => 'El campo Valor de la Nota es requerido en la fila ' . $rowNumber . '.',
                    '5.numeric' => 'El campo Valor de la Nota debe ser un valor numérico en la fila ' . $rowNumber . '.'
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
