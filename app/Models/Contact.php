<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'fullname',
        'organization',
        'inn',
        'email',
        'address',
        'idbank',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function reports()
        {
            return $this->hasMany(Report::class);
        }

    public function banks()
    {
        return $this->belongsToMany(Bank::class)->withPivot('status', 'message', 'created_at','updated_at');
    }

}
