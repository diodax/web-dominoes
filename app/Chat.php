<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'title'
    ];

    public function members()
    {
        return $this->hasMany(ChatMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
