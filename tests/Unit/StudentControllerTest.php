<?php

namespace Tests\Unit;

use App\Services\CSVService;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    /** @test */
    public function it_imports_csv_successfully()
    {
        $file = UploadedFile::fake()->create('students.csv');

        /** @var \App\Http\Requests\StoreStudentRequest|\PHPUnit\Framework\MockObject\MockObject $requestMock */
        $requestMock = $this->getMockBuilder(StoreStudentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->expects($this->once())
            ->method('file')
            ->willReturn($file);

        /** @var \App\Services\CSVService|\PHPUnit\Framework\MockObject\MockObject $csvServiceMock */
        $csvServiceMock = $this->getMockBuilder(CSVService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $csvServiceMock->expects($this->once())
            ->method('importStudentsFromCSV')
            ->with($file->getRealPath());

        $controller = new StudentController($csvServiceMock);
        $response = $controller->importCSV($requestMock);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_exports_csv_successfully()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|CSVService $csvServiceMock */
        $csvServiceMock = $this->getMockBuilder(CSVService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $csvServiceMock->expects($this->once())
            ->method('exportStudentsToCSV');

        $controller = new StudentController($csvServiceMock);
        $response = $controller->exportCSV();

        // Assert response
        $this->assertFileExists($response->getFile());
    }
}
