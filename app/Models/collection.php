<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'ucr_ref_id', // This is the foreign key
        'amount_collected',
        'tr_posting_date',
        'or_number',
        'collection_attachment',
    ];

    protected $casts = [
        'collection_attachment' => 'array',
    ];

    public function parkdoc()
    {
        return $this->belongsTo(parkdocument::class, 'ucr_ref_id');
    }

    public function accruals()
    {
        return $this->belongsTo(accrual::class, 'ucr_ref_id');
        //->withPivot(parkdocument::class ,'ucr_ref_id');
    }
}
