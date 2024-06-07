<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class draftbill extends Model
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
    public function draft()
    {
        return $this->belongsToMany(draftbilldetails::class, 'draftbill_relation');
    }
    public function draftbillno()
    {
        return $this->belongsToMany(draftbill::class, 'draftbill_no')
        ->withPivot('id', 'draftbill_id', 'draftbilldetails_id')
        ;
    }

    public function draftbilldetails()//for draftbill table
    {
        return $this->hasMany(draftbilldetails::class, 'draftbill_id', 'id');
    }
    public function accrual()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
}
