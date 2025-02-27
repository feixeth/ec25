<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reports extends Model
{
    use HasFactory;
    
    protected $table = 'reports';
    
    protected $fillable = [
        'user_id',
        'reported_entity_id',
        'reported_entity_type',
        'report_content',
    ];
    
    /**
     * Obtient l'utilisateur qui a créé ce rapport.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obtient l'entité signalée par ce rapport (polymorphique).
     */
    public function reportedEntity()
    {
        return $this->morphTo('reported_entity');
    }
}