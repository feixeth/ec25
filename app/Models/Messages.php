<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Messages extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
// Rajout pour palier a lerreur du test des messages soft delete < 
    public function getDeletedAtColumn()
    {
        return 'deleted_at';
    }
}
