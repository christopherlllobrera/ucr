<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class draftbill extends Model
{
    use HasFactory;

    protected $fillable = [
        'ucr_ref_id', // This is the foreign key
        'draftbill_no',
        'draftbill_amount',
        'bill_date_created',
        'bill_date_submitted',
        'bill_date_approved',
        'draftbill_particular',
        'bill_attachment',
    ];

    protected $casts = [
        'bill_attachment' => 'array',
    ];
    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }

    public function parkdoc()
    {
        return $this->belongsTo(parkdocument::class, 'UCR_Park_Doc');
    }


}
