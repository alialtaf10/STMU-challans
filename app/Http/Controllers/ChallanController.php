<?php

namespace App\Http\Controllers;

use App\Models\SemesterFee;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Installment;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ChallanController extends Controller
{
    public function index()
    {
        $students = Student::with(['currentTerm'])
            ->orderBy('reg_no')
            ->paginate(20);

        return view('challans.index', compact('students'));
    }

    public function create(Student $student)
    {
        $scholarship = "No Scholarship";
        $heads = $this->getFeeHeads();
    
        $semesterFee = StudentFee::with(['semester', 'feeType', 'term'])
            ->where('student_id', $student->id)
            ->where('status', 'updated')
            ->first();
    
        if (!$semesterFee) {
            $feeTypeId = StudentFee::where('student_id', $student->id)->value('fee_type_id');
    
            if (!$feeTypeId) {
                return redirect()->back()->with('error', 'No Fee Type assigned to this student.');
            }
    
            $semesterFee = SemesterFee::with(['term', 'feeType'])
                ->where('fee_type_id', $feeTypeId)
                ->first();
        }

        $installment = Installment::where('student_id', $semesterFee->student_id)->first();
        $installment_exists = "No";
        if($installment){
            $installment_exists = "Yes";
        }
    
        $finalScholarship = $this->calculateScholarship($student);

        if($finalScholarship){
            $scholarship = "Merit Scholarship";
        }
    
        $scholarship = \DB::table('scholarships')
            ->join('scholarship_types', 'scholarships.scholarship_type_id', '=', 'scholarship_types.id')
            ->select('scholarships.amount', 'scholarship_types.name as type_name')
            ->where('scholarships.student_id', $student->id)
            ->where('scholarships.status', 1)
            ->latest('scholarships.created_at')
            ->first();
    
        // Collect all fee heads safely from either model
        $feeHeads = [
            'tuition_fee',
            'admission_fee',
            'univeristy_registration_fee',
            'security_deposit',
            'medical_checkup',
            'semester_enrollment_fee',
            'examination_tuition_fee',
            'co_curricular_activities_fee',
            'hostel_fee',
            'pmc_registration',
            'pharmacy_council_reg_fee',
            'clinical_charge',
            'transport_charge',
            'library_fee',
            'migration_fee',
            'document_verification_fee',
            'application_prospectus_fee',
            'degree_convocation_fee',
            'research_thesis',
            'others_specify',
            'late_fee',
            'tuition_fee_discount',
            'special_discount',
        ];
    
        // Prepare values to send to the view
        $feeData = ['id' => $semesterFee->id, 'term_id' => $semesterFee->term_id, 'fee_type_id' => $semesterFee->fee_type_id];
    
        foreach ($feeHeads as $field) {
            $feeData[$field] = $semesterFee->$field ?? 0;
        }

        
    
        return view('challans.create', compact(
            'student',
            'semesterFee',
            'feeData',
            'heads',
            'finalScholarship',
            'scholarship',
            'installment_exists'
        ));
    }
    

    public function show(Student $student, $feeId)
    {
        $semesterFee = StudentFee::with(['term', 'feeType'])
            ->where('id', $feeId)
            ->where('student_id', $student->id)
            ->first();

        if (!$semesterFee) {
            $semesterFee = SemesterFee::with(['term', 'feeType'])
                ->where('id', $feeId)
                ->firstOrFail();
        }

        return $this->generateChallan($student, $semesterFee);
    }

    public function download(Student $student, $feeId)
    {
        $semesterFee = StudentFee::with(['term', 'feeType'])
            ->where('id', $feeId)
            ->where('student_id', $student->id)
            ->first();

        if (!$semesterFee) {
            $semesterFee = SemesterFee::with(['term', 'feeType'])
                ->where('id', $feeId)
                ->firstOrFail();
        }

        $data = $this->prepareChallanData($student, $semesterFee);

        $pdf = Pdf::loadView('challan.pdf_template', $data)
            ->setPaper('a3', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download("challan-{$student->reg_no}.pdf");
    }

    protected function generateChallan($student, $semesterFee, $isInstallment = false, $calculatedTotal = null)
    {
        // Use the calculated total if provided, otherwise calculate normally
        $total = $calculatedTotal ?? $this->calculateTotalFee($student, $semesterFee);
    
        // Get and increment the challan_id from the database
        $challanIdRecord = \DB::table('challan_ids')->first();
        $nextChallanId = $challanIdRecord ? $challanIdRecord->challan_id + 1 : 1;
        
        // Update the database with the next challan_id
        \DB::table('challan_ids')->updateOrInsert(
            ['id' => 1], 
            ['challan_id' => $nextChallanId]
        );
    
        // Generate IDs - don't store them in student_fees
        $kuickpayId = '28010' . $nextChallanId;
        
        // Format the display challan number (not stored in DB)
        $displayChallanNo = $isInstallment 
            ? $nextChallanId
            : $nextChallanId;
    
        $data = [
            'student' => $student,
            'fee' => $semesterFee,
            'total' => $total,
            'issue_date' => now()->format('d-M-y'),
            'due_date' => now()->addDays(15)->format('d-M-y'),
            'challan_no' => $displayChallanNo,
            'kuickpay_id' => $kuickpayId,
            'amount_in_words' => $this->numberToWords($total),
        ];
    
        return view('challan.template', $data);
    }
    
    
    protected function calculateTotalFee($student)
    {
        $studentFee = \App\Models\StudentFee::where('student_id', $student->id)->latest()->first();
    
        if (!$studentFee) {
            return 0;
        }
    
        $total = 0;
        $isVaried = strtolower($studentFee->feeType->title ?? '') === 'varied';
    
        $feeHeads = [
            'tuition_fee',
            'admission_fee',
            'univeristy_registration_fee',
            'security_deposit',
            'medical_checkup',
            'semester_enrollment_fee',
            'examination_tuition_fee',
            'co_curricular_activities_fee',
            'hostel_fee',
            'pmc_registration',
            'pharmacy_council_reg_fee',
            'clinical_charge',
            'transport_charge',
            'library_fee',
            'migration_fee',
            'document_verification_fee',
            'application_prospectus_fee',
            'degree_convocation_fee',
            'research_thesis',
            'others_specify',
            'late_fee',
        ];
    
        foreach ($feeHeads as $head) {
            if ($head === 'tuition_fee' && $isVaried) {
                $total += ($studentFee->tuition_fee ?? 0) * ($student->credit_hrs ?? 0);
            } else {
                $total += $studentFee->$head ?? 0;
            }
        }
    
        // Subtract discounts
        $total -= $studentFee->special_discount ?? 0;
        $total -= $studentFee->tuition_fee_discount ?? 0;
    
        return $total;
    }
    

    protected function calculateScholarship($student)
    {
        // Get any existing scholarship for the student
        $scholarship = Scholarship::where('student_id', $student->id)
            ->where('status', 1)
            ->with('scholarshipType')
            ->latest()
            ->first();
    
        // Check for special scholarships (types 4, 6, 7) which override merit scholarships
        if ($scholarship && in_array($scholarship->scholarshipType->id, [2, 4, 6, 7])) {
            return [
                'percentage' => $scholarship->waiver,
                'name' => $scholarship->scholarshipType->name
            ];
        }
    
        // Calculate merit scholarship based on HSSC and GPA
        $hssc = $student->hssc_marks;
        $gpa = $student->gpa;
    
        $hsscScholarship = match (true) {
            $hssc >= 85 => 100,
            $hssc >= 80 => 75,
            $hssc >= 75 => 50,
            $hssc >= 70 => 25,
            default => 0
        };
    
        $gpaScholarship = match (true) {
            $gpa >= 3.90 => 100,
            $gpa >= 3.80 => 75,
            $gpa >= 3.70 => 50,
            $gpa >= 3.50 => 25,
            default => 0
        };
    
        $percentage = min($hsscScholarship, $gpaScholarship);
        
        return [
            'percentage' => $percentage,
            'name' => $percentage > 0 ? 'Merit Scholarship' : 'No Scholarship'
        ];
    }

    protected function numberToWords($number)
    {
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
    
        $digits = ['', 'Thousand', 'Million', 'Billion'];
    
        if (!is_numeric($number)) return 'Invalid number';
    
        $number = number_format($number, 2, '.', '');
        list($int, $dec) = explode('.', $number);
    
        $int = (int)$int;
        if ($int === 0) {
            $str = 'Zero';
        } else {
            $str = '';
            $i = 0;
    
            while ($int > 0) {
                $chunk = $int % 1000;
    
                if ($chunk != 0) {
                    $hundreds = floor($chunk / 100);
                    $tens = $chunk % 100;
    
                    $segment = '';
    
                    if ($hundreds) {
                        $segment .= $words[$hundreds] . ' Hundred ';
                    }
    
                    if ($tens < 21) {
                        $segment .= $words[$tens];
                    } else {
                        $segment .= $words[floor($tens / 10) * 10];
                        if ($tens % 10) {
                            $segment .= ' ' . $words[$tens % 10];
                        }
                    }
    
                    $str = trim($segment) . ' ' . $digits[$i] . ' ' . $str;
                }
    
                $int = floor($int / 1000);
                $i++;
            }
    
            $str = trim($str);
        }
    
        $decimal = (int)$dec;
        if ($decimal > 0) {
            $str .= " and {$decimal}/100";
        } else {
            $str .= " only";
        }
    
        return $str;
    }

    public function storeFees(Request $request, Student $student)
    {
        try {
            $fees = $request->input('fees', []);
            $userId = auth()->id();
    
            // Determine the next challan_id (start at 33000)
            $lastChallanId = StudentFee::max('challan_id');
            $nextChallanId = $lastChallanId && $lastChallanId >= 33000 ? $lastChallanId + 1 : 33000;
    
            foreach ($fees as $feeData) {
                $studentFee = StudentFee::where('student_id', $student->id)->first();
    
                if (!$studentFee) {
                    $studentFee = new StudentFee();
                    $studentFee->student_id = $student->id;
                    $studentFee->created_by = $userId;
    
                    $studentFee->challan_id = $nextChallanId;
                    $studentFee->kuickpay_id = (int)('28010' . $nextChallanId);
                    $nextChallanId++;
                }
    
                $studentFee->updated_by = $userId;
    
                $studentFee->fee_type_id = $feeData['fee_type_id'];
                $studentFee->semester_id = $feeData['semester_id'];
    
                // Calculate final tuition fee = tuition * credit_hrs (if varied) - scholarship
                $tuition = $feeData['tuition_fee'] ?? 0;
                $isVaried = \DB::table('fee_types')->where('id', $feeData['fee_type_id'])->value('title') === 'varied';
    
                if ($isVaried && $student_fee->status == null) {
                    $tuition = $tuition * $student->credit_hrs;
                }
    
                $scholarship = $feeData['scholarship_percentage'] ?? 0;
                $discountedTuition = $tuition - ($tuition * $scholarship / 100);
    
                $studentFee->tuition_fee = $discountedTuition;
                
    
                // Store other heads as-is
                $studentFee->admission_fee = $feeData['admission_fee'] ?? 0;
                $studentFee->univeristy_registration_fee = $feeData['university_registration_fee'] ?? 0;
                $studentFee->term_id = $student->term_id;
                $studentFee->security_deposit = $feeData['security_deposit'] ?? 0;
                $studentFee->medical_checkup = $feeData['medical_checkup'] ?? 0;
                $studentFee->semester_enrollment_fee = $feeData['semester_enrollment_fee'] ?? 0;
                $studentFee->examination_tuition_fee = $feeData['examination_tuition_fee'] ?? 0;
                $studentFee->co_curricular_activities_fee = $feeData['co_curricular_activities_fee'] ?? 0;
                $studentFee->hostel_fee = $feeData['hostel_fee'] ?? 0;
                $studentFee->pmc_registration = $feeData['pmc_registration'] ?? 0;
                $studentFee->pharmacy_council_reg_fee = $feeData['pharmacy_council_reg_fee'] ?? 0;
                $studentFee->clinical_charge = $feeData['clinical_charge'] ?? 0;
                $studentFee->transport_charge = $feeData['transport_charge'] ?? 0;
                $studentFee->library_fee = $feeData['library_fee'] ?? 0;
                $studentFee->migration_fee = $feeData['migration_fee'] ?? 0;
                $studentFee->document_verification_fee = $feeData['document_verification_fee'] ?? 0;
                $studentFee->application_prospectus_fee = $feeData['application_prospectus_fee'] ?? 0;
                $studentFee->degree_convocation_fee = $feeData['degree_convocation_fee'] ?? 0;
                $studentFee->research_thesis = $feeData['research_thesis'] ?? 0;
                $studentFee->others_specify = $feeData['others_specify'] ?? 0;
                $studentFee->late_fee = $feeData['late_fee'] ?? 0;
                $studentFee->tuition_fee_discount = $feeData['tuition_fee_discount'] ?? 0;
                $studentFee->special_discount = $feeData['special_discount'] ?? 0;
                $studentFee->status = 'updated';
                $studentFee->due_date = $request->due_date ?? null;
                $studentFee->challan_id = $nextChallanId;
                $studentFee->kuickpay_id = (int)('28010' . $nextChallanId);
    
                $studentFee->save();
            }
    
            return redirect()->back()->with('success', 'Fees updated and sent for approval successfully.');
        } catch (\Throwable $e) {
            \Log::error('Fee update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
    
            return redirect()->back()->with('error', 'An error occurred while updating fees. Check logs.');
        }
    }
    

    protected function getFeeHeads()
    {
        return [
            'tuition_fee' => 'Tuition Fee',
            'admission_fee' => 'Admission Fee',
            'university_registration_fee' => 'University Registration',
            'security_deposit' => 'Security Deposit',
            'medical_checkup' => 'Medical Checkup',
            'semester_enrollment_fee' => 'Semester Enrollment',
            'examination_tuition_fee' => 'Examination Fee', // Fixed key to match database
            'co_curricular_activities_fee' => 'Co-curricular Activities',
            'hostel_fee' => 'Hostel Fee',
            'pharmacy_council_reg_fee' => 'Pharmacy Council Reg',
            'clinical_charge' => 'Clinical Charge',
            'library_fee' => 'Library Fee',
            'migration_fee' => 'Migration Fee',
            'document_verification_fee' => 'Document Verification',
            'application_prospectus_fee' => 'Application Prospectus',
            'degree_convocation_fee' => 'Convocation Fee',
            'research_thesis' => 'Research Thesis',
            'others' => 'Others',
            'special_discount' => 'Discount'
        ];
    }
    protected function getOtherFeeHeads()
    {
        return [
            'admission_fee',
            'university_registration_fee',
            'security_deposit',
            'medical_checkup',
            'semester_enrollment_fee',
            'examination_tuition_fee',
            'co_curricular_activities_fee',
            'hostel_fee',
            'pharmacy_council_reg_fee',
            'clinical_charge',
            'library_fee',
            'migration_fee',
            'document_verification_fee',
            'application_prospectus_fee',
            'degree_convocation_fee',
            'research_thesis',
        ];
    }

    public function excelView()
    {
        $heads = $this->getFeeHeads();
    
        $students = Student::with(['currentTerm'])->orderBy('name')->get();
    
        $data = [];
    
        foreach ($students as $student) {
            $fee = StudentFee::where('student_id', $student->id)
                ->where('status', 'updated')
                ->first();
    
            if (!$fee) {
                $feeTypeId = StudentFee::where('student_id', $student->id)->value('fee_type_id');
                if (!$feeTypeId) continue;
    
                $fee = SemesterFee::where('fee_type_id', $feeTypeId)->first();
            }
    
            if (!$fee) continue;
    
            // Check Installment
            $hasInstallment = Installment::where('student_id', $student->id)->exists();
            $installment = $hasInstallment ? 'Yes' : 'No';
    
            // Get Scholarship Name and calculate discount
            // $scholarship = \DB::table('scholarships')
            //     ->join('scholarship_types', 'scholarships.scholarship_type_id', '=', 'scholarship_types.id')
            //     ->select('scholarships.amount', 'scholarship_types.name as type_name', 'scholarship_types.id as type_id', 'scholarship_types.waiver')
            //     ->where('scholarships.student_id', $student->id)
            //     ->where('scholarships.status', 1)
            //     ->latest('scholarships.created_at')
            //     ->first();

            $scholarship = Scholarship::where('student_id', $student->id)->first();
            $scholarship_name = "No Scholarship";

            if($scholarship){
                $scholarship_name = $scholarship->scholarshipType->name;
            }
    
            // GPA + HSSC calculation
            $scholarshipInfo = $this->calculateScholarship($student);
            $finalScholarship = $scholarshipInfo['percentage'];
            $scholarshipName = $scholarshipInfo['name'];
    
            // Tuition fee after scholarship
            $tuition = $fee->tuition_fee ?? 0;
            $discountAmount = ($tuition * $finalScholarship) / 100;
    
            $row = [
                'student' => $student,
                'term' => $student->currentTerm->name ?? '',
                'fee_type' => $fee->feeType->title ?? '',
                'installment' => $installment,
                'scholarship' => $scholarshipName,
                'tuition_fee_discount' => (int) $discountAmount,
                'fees' => []
            ];
    
            foreach ($heads as $key => $label) {
                $row['fees'][$label] = intval($fee->$key ?? 0);
            }
    
            // Set the calculated discount in the correct field (optional visual purpose)
            $row['fees']['Tuition Fee Discount'] = (int) $discountAmount;
    
            $data[] = $row;
        }
    
        return view('challans.excel_view', compact('data', 'heads'));
    }
    

    public function approveChallans(Request $request){
        $students = Student::all();
        
    }

    public function viewInstallments(Student $student)
    {
        $semesterFee = StudentFee::with(['semester', 'feeType', 'term'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['updated', 'approved'])
            ->firstOrFail();

            $installment = Installment::where('student_id', $student->id)->first();
            $installmentStatus = $installment ? $installment->status : 0;

        $heads = $this->getFeeHeads();

        $installment1 = [];
        $installment2 = [];

        $isVaried = strtolower($semesterFee->feeType->title) === 'varied';
        $tuitionFee = $isVaried ? $semesterFee->tuition_fee * $student->credit_hrs : $semesterFee->tuition_fee;
        $scholarshipInfo = $this->calculateScholarship($student);
        $scholarshipPercentage = $scholarshipInfo['percentage'];
        $scholarshipName = $scholarshipInfo['name'];
        $scholarshipAmount = ($tuitionFee * $scholarshipPercentage) / 100;
        $tuitionAfterScholarship = $tuitionFee - floor($scholarshipAmount);

        foreach ($heads as $key => $label) {
            $value = floatval($semesterFee->$key ?? 0);
            if ($key === 'tuition_fee') {
                $value = $tuitionAfterScholarship;
            }

            if (in_array($key, ['special_discount', 'tuition_fee_discount'])) {
                $value = 0 - $value;
            }

            $half = floor($value / 2);
            $remainder = $value - ($half * 2);
            $installment1[$key] = $half + $remainder;
            $installment2[$key] = $half;
        }

        return view('challans.installments', compact(
            'student',
            'semesterFee',
            'heads',
            'installment1',
            'installment2',
            'scholarshipPercentage',
            'installmentStatus'
        ));
    }

    public function generateInstallmentChallan(Student $student, $installmentNumber)
    {
        $semesterFee = StudentFee::with(['semester', 'feeType', 'term'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['updated', 'approved'])
            ->firstOrFail();
    
        $heads = $this->getFeeHeads();
    
        $isVaried = strtolower($semesterFee->feeType->title) === 'varied';
        $tuitionFee = $isVaried ? $semesterFee->tuition_fee * $student->credit_hrs : $semesterFee->tuition_fee;
        $scholarshipInfo = $this->calculateScholarship($student);
        $scholarshipPercentage = $scholarshipInfo['percentage'];
        $scholarshipName = $scholarshipInfo['name'];
        
        $scholarshipAmount = ($tuitionFee * $scholarshipPercentage) / 100;
        $tuitionAfterScholarship = $tuitionFee - floor($scholarshipAmount);
    
        // Create a temporary fee object for display only
        $installmentFee = clone $semesterFee;
        $installmentFee->installment_number = $installmentNumber;
        
        $total = 0; // Initialize total
        
        foreach ($heads as $key => $label) {
            $value = floatval($semesterFee->$key ?? 0);
            if ($key === 'tuition_fee') {
                $value = $tuitionAfterScholarship;
            }
    
            if (in_array($key, ['special_discount', 'tuition_fee_discount'])) {
                $value = 0 - $value;
            }
    
            $half = floor($value / 2);
            $remainder = $value - ($half * 2);
            
            if ($installmentNumber == 1) {
                $installmentValue = $half + $remainder;
            } else {
                $installmentValue = $half;
            }
            
            $installmentFee->$key = $installmentValue;
            
            // Calculate total based on installment values
            if (in_array($key, ['special_discount', 'tuition_fee_discount'])) {
                $total -= abs($installmentValue);
            } else {
                $total += $installmentValue;
            }
        }
    
        // Generate the challan with the calculated installment total
        return $this->generateChallan($student, $installmentFee, true, $total);
    }

}
