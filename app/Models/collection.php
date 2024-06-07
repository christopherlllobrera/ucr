<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'ucr_ref_id',
        'draftbill_no',
        'accounting_doc',
    ];

    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id')->orderBy('created_at', 'desc');
    }

    public function collection()
    {
        return $this->belongsToMany(collectiondetails::class, 'collection_relation');
    }

    public function draftbills()
    {
        return $this->belongsTo(draftbilldetails::class, 'draftbill_no');
    }

    public function invoices()
    {
        return $this->belongsTo(invoicedetails::class, 'accounting_doc');
    }

    public function draftbilldetails()
    {
        return $this->belongsTo(draftbill::class, 'id');
    }
}
