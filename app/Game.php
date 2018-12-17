<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'player_1_id', 'player_2_id', 'score_to_win', 'rounds_completed', 'is_finished',
    ];

    public function player($id)
    {
        return Player::find($id);
    }
}
