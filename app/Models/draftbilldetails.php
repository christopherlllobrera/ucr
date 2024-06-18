<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class draftbilldetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'draftbill_particular',
        'draftbill_number',
        'bill_date_created',
        'draftbill_amount',
        'bill_date_submitted',
        'bill_date_approved',
        'bill_attachment',
    ];
    protected $casts = [
        'bill_attachment' => 'array',
    ];
    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
    public function draftbills()
    {
        return $this->belongsToMany(draftbill::class, 'draftbill_relation');
    }
}
