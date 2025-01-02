<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{

    protected $table = 'games'; 
    
    protected $fillable = [
        'name',
        'code',
        'logo'
    ];
}