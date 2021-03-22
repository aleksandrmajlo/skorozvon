<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'request',
        'answer',
        'user_id',
        'ip',
        'type'
    ];

    protected $casts = [
       'request'=>'array',
       'answer'=>'array',
    ];
}
