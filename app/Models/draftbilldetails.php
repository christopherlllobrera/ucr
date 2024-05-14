<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class draftbilldetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'draftbill_particular',
        'draftbill_no',
        'bill_date_created',
        'draftbill_amount',
        'bill_date_submitted',
        'bill_date_approved',
        'bill_attachment',
    ];
    protected $casts = [
        'bill_attachment' => 'array',
    ];
}
