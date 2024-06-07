<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'ucr_ref_id',
        'draftbill_no',
    ];

    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id')->orderBy('created_at', 'desc')->where('ucr_ref_id', '!=', null);
    }

    public function invoicerelation()
    {
        return $this->belongsToMany(invoicedetails::class, 'invoice_relation');
    }

    public function draftbills()
    {
        return $this->belongsTo(draftbilldetails::class, 'draftbill_no')->orderBy('created_at', 'desc')
        //->where('draftbill_no', '!=', null)
        ;
    }

    public function draft()
    {
        return $this->belongsTo(draftbill::class, 'draftbill_relation');
    }
}
