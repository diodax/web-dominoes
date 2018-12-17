<?php

namespace App\Services;

use App\Models\GameState;
use App\Game;
use App\User;
use App\Player;

class GameCenterService
{
    public function __construct()
    {
        //
    }

    public function get($id, $request)
    {
        // if the session doesnt have the game object, create a new instance and return that
        if ($request->session()->has($id)) {
            return $request->session()->get((string)$id);
        } else {
            return $this->create($id, $request);
        }
    }

    public function create($id, $request)
    {
        $game = Game::find($id);
        $gameState = new GameState($game);
        $request->session()->put((string)$id, $gameState);
        return $gameState;
    }

    public function save($id, GameState $game, $request)
    {
        $gameState = $request->session()->put((string)$id, $game);
        return $gameState;
    }

    public function destroy($id, $request)
    {
        $gameState = $request->session()->pull((string)$id);
        return $gameState;
    }

    public function isUserPlayingGame($userId, $gameId)
    {
        $game = Game::find($gameId);
        $user = User::find($userId);
        $player1 = Player::find($game->player_1_id);
        $player2 = Player::find($game->player_2_id);

        return (($user->id === $player1->user_id) || ($user->id === $player2->user_id));
    }
}
