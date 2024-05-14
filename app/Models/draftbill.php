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
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
    public function draft()
    {
        return $this->belongsToMany(draftbilldetails::class, 'draftbill_relation');
    }
}
