<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accrual extends Model
{
    use HasFactory;

    protected $fillable = [
        'ucr_ref_id',
        'client_name',
        'person_in_charge',
        'wbs_no',
        'particulars',
        'period_started',
        'period_ended',
        'business_unit',
        'contract_type',
        'month',
        'accrual_amount',
        'accruals_attachment',
        'UCR_Park_Doc',
        'date_accrued',
    ];

    protected $casts = [
        'accruals_attachment' => 'array',
    ];

    public function parkdoc()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
}
