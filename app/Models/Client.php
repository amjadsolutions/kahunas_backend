<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'coach_id',
        'name',
        'email',
        'password',

    ];
    // Ensure that the password is always hidden when returning client data

    // Define the relationship with the coach (user)
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Get the sessions associated with this client.
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'client_id');
    }

   

}
