<?php

namespace App\Models;

use App\Bone;
use App\Player;

class PlayerInfo
{
    public $id;
    public $username;
    public $score;
    public $isWinner;
    public $hand;
    public $validPlays;

    public function __construct(Player $player)
    {
        $this->id = $player->id;
        $this->username = $player->user->username;
        $this->score = $player->current_score;
        $this->isWinner = $player->is_winner;
        $this->hand = [];
        $this->validPlays = [];
    }
}
