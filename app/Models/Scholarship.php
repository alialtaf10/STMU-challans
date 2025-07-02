<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = ['student_id', 'scholarship_type_id'];

    public function scholarshipType()
    {
        return $this->belongsTo(ScholarshipType::class, 'scholarship_type_id');
    }
}
