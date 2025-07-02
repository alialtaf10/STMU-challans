<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    public $fillable = ['name', 'father_name', 'email', 'phone', 'reg_no', 'program', 'semester_id', 'credit_hrs', 'gpa', 'hssc_marks', 'term_id', 'arrears'];

    /**
     * Get the current term for the student
     */
    public function currentTerm()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    /**
     * Get all semester fees for the student
     */
    public function semesterFees()
    {
        return $this->hasMany(SemesterFee::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function feeType(): HasOne
    {
        return $this->hasOne(StudentFee::class, 'student_id')
            ->with('feeType');
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

}
