<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class draftbilldetails extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
