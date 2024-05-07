<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collectiondetails extends Model
{
    use HasFactory;
    protected $fillable = [
       
        'amount_collected',
        'tr_posting_date',
        'or_number',
        'collection_attachment',
    ];

    protected $casts = [
        'collection_attachment' => 'array',
    ];

}
