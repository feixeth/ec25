<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'owner',
        'name',
        'logo',
        'country',
        'website',
        'social'
    ];
}
