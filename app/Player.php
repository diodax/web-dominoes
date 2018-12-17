<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'current_score', 'is_winner'
    ];

    /**
     * Get the user that owns the player.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
