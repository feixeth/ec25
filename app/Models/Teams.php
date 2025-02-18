<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
    
class Teams extends Model
{

    use HasFactory;
    
    protected $table = 'teams';

    protected $fillable = [
        'owner',
        'name',
        'logo',
        'country',
        'website',
        'social',
    ];
    
    public function members()
    {
        return $this->belongsToMany(User::class, 'teams_members', 'team_id', 'user_id');
    }

}
