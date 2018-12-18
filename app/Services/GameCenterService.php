<?php

namespace App\Services;

use App\Models\GameState;
use App\Game;
use App\User;
use App\Player;
use App\Transformers\GameStateTransformer;
use App\Transformers\TreeTransformer;
use App\Transformers\NodeTransformer;
use Karriere\JsonDecoder\JsonDecoder;

class GameCenterService
{
    public $jsonDecoder;

    public function __construct()
    {
        $this->jsonDecoder = new JsonDecoder();
        $this->jsonDecoder->register(new GameStateTransformer());
        $this->jsonDecoder->register(new TreeTransformer());
        $this->jsonDecoder->register(new NodeTransformer());
    }

    public function get($id, $request)
    {
        $game = Game::find($id);
        if (is_null($game->game_state)) {
            return $this->create($id, $request);
        } else {
            $gameState = $this->jsonDecoder->decode($game->game_state, GameState::class);
            return $gameState;
        }
    }

    public function create($id, $request)
    {
        $game = Game::find($id);
        $gameState = new GameState;
        $gameState->create($game);
        $game->game_state = json_encode($gameState);
        $game->save();
        return $gameState;
    }

    public function save($id, GameState $game, $request)
    {
        $gameRecord = Game::find($id);
        $gameRecord->game_state = json_encode($game);
        $gameRecord->save();
        return $game;
    }

    public function destroy($id, $request)
    {
        $game = Game::find($id);
        $gameState = $this->jsonDecoder->decode($game->game_state, GameState::class);
        $game->game_state = null;
        $game->save();
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
