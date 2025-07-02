<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    // protected $fillable = ['student_id', 'fee_type_id', 'semester_id', 'tuition_fee', 'semester_enrollment_fee', 'examination_tuition_fee', 'co_curricular_activities_fee', 'discount', 'status'];
    protected $fillable = [
        'challan_id',
        'kuickpay_id',
        'student_id',
        'fee_type_id',
        'semester_id',
        'created_by',
        'updated_by',
        'term_id',
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
        'due_date',
        'status',
    ];
    
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function term() {
        return $this->belongsTo(Term::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
