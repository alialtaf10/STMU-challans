<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    //


    public function semesterFees(): HasMany
    {
        return $this->hasMany(SemesterFee::class);
    }
}
