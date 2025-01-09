<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coaches extends Model
{

    protected $table = 'coaches'; 
    

    protected $fillable = [
        'user_id',
        'game_id',
        'status',
        'achievement',
    ];

    /**
     * Relation avec le modèle User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le modèle Game.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Accesseur pour le champ `status` (si besoin de le transformer ou de fournir des alias).
     */
    public function getStatusAttribute($value)
    {
        return ucfirst($value); //  Available au lieu de available.
    }


    

}