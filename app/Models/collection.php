<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'ucr_ref_id',
    ];
    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
    }
    public function collection()
    {
        return $this->belongsTo(collectiondetails::class, 'collection_relation');
    }
    public function draftbills(){
        return $this->belongsTo(draftbilldetails::class, 'draftbill_no');
    }

}
