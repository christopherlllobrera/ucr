<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

class accrual extends Model
{
    use HasFactory, HasPermissions;

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

    public function draftbill()
    {
        return $this->hasMany(draftbill::class, 'ucr_ref_id');
    }

    public function draft()
    {
        return $this->belongsToMany(draftbilldetails::class, 'draftbill_relation')
        //->withPivot('id', 'draftbill_id', 'draftbilldetails_id')
        ;
    }
    public function draftbillno()
    {
        return $this->belongsTo(draftbilldetails::class, 'draftbill_no');
    }

    // public function draftbilldetails()
    // {
    //     return $this->hasManyThrough(draftbilldetails::class, draftbill::class, 'ucr_ref_id', 'draftbill_no', 'ucr_ref_id', 'id');
    // }
}
