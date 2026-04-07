<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->create(['status' => 'active']);
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_client_can_view_import_form(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/client/employees/import');
        $response->assertStatus(200);
        $response->assertViewIs('client.employees.import');
    }

    public function test_import_requires_xlsx_file(): void
    {
        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', []);
        $response->assertSessionHasErrors('file');
    }

    public function test_import_rejects_non_xlsx(): void
    {
        $file = UploadedFile::fake()->create('employees.csv', 100, 'text/csv');
        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', [
            'file' => $file,
        ]);
        $response->assertSessionHasErrors('file');
    }

    public function test_successful_import_creates_employees(): void
    {
        Storage::fake('local');
        
        $export = new class implements FromArray, WithHeadings {
            public function array(): array
            {
                return [
                    [
                        'employee_name_arabic' => 'جون دو',
                        'employee_name_english' => 'John Doe',
                        'email_address' => 'john@example.com',
                        'gender' => 'male',
                        'annual_leave_days' => 30,
                        'password' => 'password123',
                        'position' => 'Developer',
                        'national_id_residency_number' => 'ID1234567890',
                        'basic_salary' => 5000,
                        'hire_date' => '2023-01-01',
                    ],
                ];
            }
            public function headings(): array
            {
                return [
                    'employee_name_arabic',
                    'employee_name_english',
                    'email_address',
                    'gender',
                    'annual_leave_days',
                    'password',
                    'position',
                    'national_id_residency_number',
                    'basic_salary',
                    'hire_date'
                ];
            }
        };

        Excel::store($export, 'test_import.xlsx', 'local');
        
        $file = new UploadedFile(
            Storage::disk('local')->path('test_import.xlsx'),
            'test_import.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true // true for "test" mode
        );

        $response = $this->actingAs($this->clientUser)->post('/client/employees/import', [
            'file' => $file,
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        
        $this->assertDatabaseHas('employees', [
            'client_id' => $this->client->id,
            'name_ar' => 'جون دو',
            'name_en' => 'John Doe',
            'national_id_number' => 'ID1234567890',
            'basic_salary' => 5000,
        ]);
    }
}
