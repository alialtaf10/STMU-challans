<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\ChallanLinkMail;
use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\Installment;
use App\Models\Student;

class StudentFeeController extends Controller
{
    public function approvedFees()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::pluck('student_id')->toArray();

        $studentFees = StudentFee::with(['student', 'term', 'semester'])
            ->whereIn('status', ['updated', 'approved'])
            ->whereNotIn('student_id', $studentsWithInstallments)
            ->get();

        return view('student_fees.updated', compact('studentFees'));
    }

    public function approvedInstallmentStudents()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::pluck('student_id')->toArray();

        $studentFees = StudentFee::with(['student', 'term', 'semester'])
            ->whereIn('status', ['updated', 'approved'])
            ->whereIn('student_id', $studentsWithInstallments)
            ->get();

        return view('student_fees.updated', compact('studentFees'));
    }

    public function view(Student $student)
    {
        $heads = [
            'tuition_fee' => 'Tuition Fee',
            'admission_fee' => 'Admission Fee',
            'university_registration_fee' => 'University Registration',
            'security_deposit' => 'Security Deposit',
            'medical_checkup' => 'Medical Checkup',
            'semester_enrollment_fee' => 'Semester Enrollment',
            'examination_tuition_fee' => 'Examination Fee',
            'co_curricular_activities_fee' => 'Co-curricular Activities',
            'hostel_fee' => 'Hostel Fee',
            'pmc_registration' => 'PMC Registration',
            'pharmacy_council_reg_fee' => 'Pharmacy Council Reg',
            'clinical_charge' => 'Clinical Charges',
            'transport_charge' => 'Transport Charges',
            'library_fee' => 'Library Fee',
            'migration_fee' => 'Migration Fee',
            'document_verification_fee' => 'Document Verification',
            'application_prospectus_fee' => 'Application Prospectus',
            'degree_convocation_fee' => 'Convocation Fee',
            'research_thesis' => 'Research Thesis',
            'others_specify' => 'Others(Specify)',
            'late_fee' => 'Late Fee',
            'tuition_fee_discount' => 'LESS:Tuition Fee Discount/Waiver',
            'special_discount' => 'LESS:Special Discount/Scholarship'
        ];
    
        $semesterFee = \App\Models\StudentFee::with(['semester', 'feeType', 'term'])
                        ->where('student_id', $student->id)
                        // ->where('status', 'updated')
                        ->firstOrFail();

        $installment = Installment::where('student_id', $semesterFee->student_id)->first();
        $installment_exists = "No";
        if($installment){
            $installment_exists = "Yes";
        }
    
        $scholarship = \App\Models\Scholarship::with('scholarshipType')
                        ->where('student_id', $student->id)
                        ->first();
    
        $scholarshipName = $scholarship?->scholarshipType?->name ?? 'No Scholarship';
    
        // GPA & HSSC Scholarship Calculation
        $hssc = $student->hssc_marks;
        $gpa  = $student->gpa;
    
        $hsscScholarship = match (true) {
            $hssc >= 85 => 100,
            $hssc >= 80 => 75,
            $hssc >= 75 => 50,
            $hssc >= 70 => 25,
            default     => 0
        };
    
        $gpaScholarship = match (true) {
            $gpa >= 3.90 => 100,
            $gpa >= 3.80 => 75,
            $gpa >= 3.70 => 50,
            $gpa >= 3.50 => 25,
            default      => 0
        };

        $finalScholarship = min($hsscScholarship, $gpaScholarship);

        

        if($scholarship?->scholarshipType?->id == 4 || $scholarship?->scholarshipType?->id == 6 || $scholarship?->scholarshipType?->id == 7)
        $finalScholarship = $scholarship->scholarshipType->waiver;

        // dd($finalScholarship);

        // if($semesterFee->status == 'approved'){
        //     $finalScholarship = 0;
        // }
    
        
        return view('student_fees.challan', compact(
            'student',
            'semesterFee',
            'heads',
            'finalScholarship',
            'scholarshipName',
            'installment_exists'
        ))->with('readonly', true);
    }
    

    public function approve($id)
    {
        $fee = StudentFee::findOrFail($id);
        $student = $fee->student; // Assuming relationship exists: StudentFee belongsTo Student
    
        // Scholarship check
        $scholarship = \App\Models\Scholarship::with('scholarshipType')
                        ->where('student_id', $student->id)
                        ->first();
    
        // GPA & HSSC values
        $hssc = $student->hssc_marks;
        $gpa  = $student->gpa;
    
        // GPA/HSSC-based waiver
        $hsscScholarship = match (true) {
            $hssc >= 85 => 100,
            $hssc >= 80 => 75,
            $hssc >= 75 => 50,
            $hssc >= 70 => 25,
            default     => 0
        };
    
        $gpaScholarship = match (true) {
            $gpa >= 3.90 => 100,
            $gpa >= 3.80 => 75,
            $gpa >= 3.70 => 50,
            $gpa >= 3.50 => 25,
            default      => 0
        };
    
        // Take minimum of both
        $finalScholarship = min($hsscScholarship, $gpaScholarship);
    
        // Override if fixed scholarship exists
        if (in_array($scholarship?->scholarshipType?->id, [4, 6, 7])) {
            $finalScholarship = $scholarship->scholarshipType->waiver;
        }
    
        // Apply discount on tuition_fee
        $originalTuition = $fee->tuition_fee;
        $discountedTuition = $originalTuition - ($originalTuition * ($finalScholarship / 100));
    
        $fee->tuition_fee = round($discountedTuition);
        $fee->status = 'approved';
        $fee->save();
    
        return redirect()->route('student_fees.updated')->with('success', 'Student fee approved with scholarship applied.');
    }

    public function approveMultiple(Request $request)
    {
        $feeIds = $request->input('fee_ids', []);
    
        if (empty($feeIds)) {
            return redirect()->back()->with('error', 'No student fees selected.');
        }
    
        $userId = auth()->id();
    
        // Get the latest challan_id and start from 33000 if it's lower
        $lastChallanId = \App\Models\StudentFee::max('challan_id');
        $nextChallanId = ($lastChallanId && $lastChallanId >= 33000) ? $lastChallanId + 1 : 33000;
    
        foreach ($feeIds as $id) {
            $fee = \App\Models\StudentFee::find($id);
            if (!$fee) continue;
    
            $student = $fee->student;
            if (!$student) continue;
    
            // GPA & HSSC-based scholarship
            $hssc = $student->hssc_marks;
            $gpa = $student->gpa;
    
            $hsscScholarship = match (true) {
                $hssc >= 85 => 100,
                $hssc >= 80 => 75,
                $hssc >= 75 => 50,
                $hssc >= 70 => 25,
                default     => 0,
            };
    
            $gpaScholarship = match (true) {
                $gpa >= 3.90 => 100,
                $gpa >= 3.80 => 75,
                $gpa >= 3.70 => 50,
                $gpa >= 3.50 => 25,
                default      => 0,
            };
    
            $finalScholarship = min($hsscScholarship, $gpaScholarship);
    
            // Fixed scholarship override
            $scholarship = \App\Models\Scholarship::with('scholarshipType')
                ->where('student_id', $student->id)
                ->first();
    
            if (in_array($scholarship?->scholarshipType?->id, [4, 6, 7])) {
                $finalScholarship = $scholarship->scholarshipType->waiver;
            }
    
            // Tuition calculation after discount
            $originalTuition = $fee->tuition_fee ?? 0;
            $discountedTuition = $originalTuition - ($originalTuition * ($finalScholarship / 100));
            $fee->tuition_fee = round($discountedTuition);
    
            // Assign challan_id and kuickpay_id if not set
            if (!$fee->challan_id) {
                $fee->challan_id = $nextChallanId;
                $fee->kuickpay_id = (int)('28010' . $nextChallanId);
                $nextChallanId++;
            }
    
            $fee->status = 'approved';
            $fee->updated_by = $userId;
            $fee->save();
        }
    
        return redirect()->route('student_fees.updated')->with('success', 'Selected student fees approved with scholarship and challan assigned.');
    }
    
    
    

    public function approvedList()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::pluck('student_id')->toArray();
    
        $approvedFees = StudentFee::with(['student', 'feeType', 'term'])
            ->where('status', 'approved')
            ->whereNotIn('student_id', $studentsWithInstallments)
            ->get();
    
        return view('student_fees.approved_list', compact('approvedFees'));
    }

    public function sentEmailList()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::pluck('student_id')->toArray();
    
        $approvedFees = StudentFee::with(['student', 'feeType', 'term'])
            ->where('status', 'email_sent')
            ->whereNotIn('student_id', $studentsWithInstallments)
            ->get();
    
        return view('student_fees.approved_list', compact('approvedFees'));
    }

    public function approvedStudentInstallments()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::pluck('student_id')->toArray();

        $approvedFees = StudentFee::with(['student', 'feeType', 'term'])
            ->where('status', 'approved')
            ->whereIn('student_id', $studentsWithInstallments)
            ->get();

        return view('student_fees.approved_list', compact('approvedFees'));
    }

    public function sentInstallmentList()
    {
        // Get all student IDs who have installments
        $studentsWithInstallments = Installment::where('status', 2)->pluck('student_id')->toArray();

        $approvedFees = StudentFee::with(['student', 'feeType', 'term'])
            ->where('status', 'approved')
            ->whereIn('student_id', $studentsWithInstallments)
            ->get();

        return view('student_fees.approved_list', compact('approvedFees'));
    }


    public function sendEmails(Request $request)
    {
        $ids = $request->input('student_fee_ids', []);
    
        if (empty($ids)) {
            return back()->with('error', 'No students selected.');
        }
    
        $fees = StudentFee::with('student')->whereIn('id', $ids)->get();
    
        foreach ($fees as $fee) {
            if ($fee->student && $fee->student->email) {
                // Use correct route with both student and semesterFee (fee) ID
                $link = route('challans.show', [
                    'student' => $fee->student_id,
                    'semesterFee' => $fee->id,
                ]);
    
                Mail::to($fee->student->email)->send(
                    new ChallanLinkMail($fee->student, $link)
                );

                $fee->status = "email_sent";
                $fee->save();
            }
        }
    
        return redirect()->route('student_fees.approved_list')->with('success', 'Emails sent successfully.');
    }

    public function sendInstallmentEmail(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'installment_number' => 'required|in:1,2'
        ]);
    
        $student = Student::findOrFail($request->student_id);
        $installment = Installment::firstOrCreate(
            ['student_id' => $student->id],
            ['term_id' => $student->term_id, 'status' => 0]
        );
    
        // Update installment status
        $installment->status = $request->installment_number;
        $installment->save();
    
        // Generate the challan link
        $fee = StudentFee::where('student_id', $student->id)
            ->where('status', 'approved')
            ->first();
    
        if (!$fee) {
            return back()->with('error', 'No approved fee record found for this student');
        }
    
        $link = route('challans.installment-challan', [
            'student' => $student->id,
            'installmentNumber' => $request->installment_number
        ]);
    
        // Send email
        try {
            Mail::to($student->email)->send(
                new ChallanLinkMail($student, $link)
            );
    
            return back()->with('success', 'Email sent successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }


}