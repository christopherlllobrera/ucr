<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parkdocument extends Model
{
    use HasFactory;

    protected $fillable = [
            'ucr_ref_id',
            'UCR_Park_Doc',
            'date_accrued',
    ];

    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }

}
