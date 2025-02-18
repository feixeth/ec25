<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Games extends Model
{
    use HasFactory;


    protected $table = 'games'; 
    
    protected $fillable = [
        'name',
        'code',
        'logo'
    ];
}