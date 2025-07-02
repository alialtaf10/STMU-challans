<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Term;
use App\Models\Semester;
use App\Models\Installment;
use App\Models\StudentFee;
use App\Models\Scholarship;
use App\Models\ScholarshipType;
use App\Models\SemesterFee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToCollection, WithHeadingRow
{
    protected $feeTypeId;
    protected $program;
    protected $currentRow = 0;

    public function __construct($feeTypeId, $program)
    {
        $this->feeTypeId = $feeTypeId;
        $this->program = $program;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $scholarshipTypes = ScholarshipType::pluck('id', 'name')->toArray();

            foreach ($rows as $row) {
                $this->currentRow++;

                if (empty($row['name_of_student']) || empty($row['registration_no'])) {
                    continue;
                }

                $currentRowData = $row->toArray();
                $regParts = explode('-', $row['registration_no']);
                $termShortCode = $regParts[1] ?? null;

                if (!$termShortCode) {
                    throw new \Exception("Invalid registration number format on row {$this->currentRow}");
                }

                $term = Term::where('short_code', $termShortCode)->first();
                if (!$term) {
                    throw new \Exception("Term '{$termShortCode}' not found on row {$this->currentRow}");
                }

                $semesterName = $row['semester'];
                $semester = Semester::where('name', $semesterName)->first();
                if (!$semester) {
                    throw new \Exception("Semester '{$semesterName}' not found on row {$this->currentRow}");
                }

                $studentData = [
                    'name' => $this->validateField($row['name_of_student'], 'name_of_student', 'string'),
                    'father_name' => $this->validateField($row['father_name'], 'father_name', 'string'),
                    'email' => $this->validateField($row['email'] ?? null, 'email', 'email'),
                    'phone' => $this->validateField($row['cell_no'], 'cell_no', 'string'),
                    'program' => $this->program,
                    'semester_id' => $semester->id,
                    'credit_hrs' => $this->validateField($row['cr_hrs'] ?? 0, 'cr_hrs', 'integer'),
                    'gpa' => $this->validateField($row['gpa_ese_fall_2024'] ?? 0, 'gpa_ese_fall_2024', 'float'),
                    'hssc_marks' => $this->convertToPercentage($this->validateField($row['hssc_marks'] ?? 0, 'hssc_marks', 'numeric')),
                    'term_id' => $term->id,
                    'status' => 1,
                ];

                $student = Student::updateOrCreate(
                    ['reg_no' => $this->validateField($row['registration_no'], 'registration_no', 'string')],
                    $studentData
                );

                // Get semester fee
                $semesterFee = SemesterFee::where('fee_type_id', $this->feeTypeId)
                    // ->where('term_id', $term->id)
                    ->first();

                if (!$semesterFee) {
                    throw new \Exception("Semester fee not found for Fee Type ID {$this->feeTypeId} and Term ID {$term->id} on row {$this->currentRow}");
                }

                // Calculate tuition_fee
                $tuition = ($this->feeTypeId == 2)
                    ? $semesterFee->tuition_fee * $student->credit_hrs
                    : $semesterFee->tuition_fee;

                // Create Student Fee record
                StudentFee::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'fee_type_id' => $this->feeTypeId,
                        'semester_id' => $semester->id,
                        'tuition_fee' => $tuition,
                        'semester_enrollment_fee' => $semesterFee->semester_enrollment_fee,
                        'examination_tuition_fee' => $semesterFee->examination_tuition_fee,
                        'co_curricular_activities_fee' => $semesterFee->co_curricular_activities_fee,
                        'status' => "updated"
                    ]
                );

                if (strtolower(trim($row['installments'] ?? '')) === 'yes') {
                    Installment::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'term_id' => $term->id,
                        ]
                    );
                }

                $this->processScholarship($student, $row['remarks'] ?? '', $scholarshipTypes, $this->currentRow);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
                'current_row' => $this->currentRow ?? 'unknown',
                'current_row_data' => $currentRowData ?? null,
                'scholarship_types' => $scholarshipTypes ?? null,
            ]);
        }
    }

    protected function processScholarship($student, $remarks, $scholarshipTypes, $rowNumber)
    {
        if (empty($remarks)) return;

        $scholarshipName = trim($remarks);

        foreach ($scholarshipTypes as $name => $id) {
            if (strcasecmp(trim($name), $scholarshipName) === 0) {
                Scholarship::updateOrCreate(
                    ['student_id' => $student->id, 'scholarship_type_id' => $id],
                    ['status' => 1]
                );
                return;
            }
        }

        Log::warning("Scholarship type '{$scholarshipName}' not found on row {$rowNumber}");
    }

    protected function validateField($value, $fieldName, $type)
    {
        if ($fieldName === 'email' && empty($value)) return null;
        if ($value === null && $fieldName !== 'email') {
            throw new \Exception("Field '{$fieldName}' is null");
        }

        switch ($type) {
            case 'integer':
                if (!is_numeric($value)) throw new \Exception("Field '{$fieldName}' must be numeric");
                return (int) $value;
            case 'float':
                if (!is_numeric($value)) throw new \Exception("Field '{$fieldName}' must be numeric");
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format in '{$fieldName}'");
                }
                return $value;
            case 'numeric':
                if (!is_numeric($value)) throw new \Exception("Field '{$fieldName}' must be numeric");
                return $value;
            default:
                return $value;
        }
    }

    protected function convertToPercentage($value)
    {
        if (empty($value)) return 0;
        return $value < 1 ? (int) round($value * 100) : (int) round($value);
    }
}
