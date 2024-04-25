<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

    protected $fillable =[
        'ucr_ref_id',
        'reversal_doc',
        'gr_amount',
        'date_reversal',
        'accounting_doc',
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

    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
}
