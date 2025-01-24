<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversations extends Model
{

    use HasFactory;

    protected $fillable = ['subject'];

    public function participants()
    {   
        return $this->belongsToMany(User::class)
            ->withPivot('last_read')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Messages::class)->latest();
    }
}
