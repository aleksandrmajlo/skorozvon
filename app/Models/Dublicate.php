<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dublicate extends Model
{
    use HasFactory;

    protected $fillable = [
        'idd', 
        'inns',
        'status',
        'response',
        'bank_id'
    ];

    protected $casts = [
    'inns' => 'array',
    'response'=>'array'
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('status');
    }

    
}
