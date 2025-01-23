<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'coach_id', 'client_id', 'session_date', 'session_time', 'duration', 'status',
    ];

    // Relationship with Client
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Relationship with Coach (User)
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

}
