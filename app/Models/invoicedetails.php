<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicedetails extends Model
{
    use HasFactory;

    protected $fillable =[
        'reversal_doc',
        'gr_amount',
        'date_reversal',
        'accounting_document',
        'invoice_date_received',
        'pojo_no',
        'gr_no_meralco',
        'billing_statement',
        'invoice_date_approved',
        'invoice_posting_date',
        'invoice_posting_amount',
        'invoice_date_forwarded',
        'invoice_attachment',
    ];

    protected $casts = [
        'invoice_attachment' => 'array',
    ];
}
