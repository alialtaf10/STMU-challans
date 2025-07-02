<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SemesterFee extends Model
{
    //

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    /**
     * Get the term for this semester fee
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
