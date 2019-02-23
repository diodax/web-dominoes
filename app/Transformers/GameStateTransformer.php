<?php

namespace App\Transformers;

use App\Models\GameState;
use App\Models\PlayerInfo;
use App\Models\Tree;
use Illuminate\Support\Collection;
use Karriere\JsonDecoder\Transformer;
use Karriere\JsonDecoder\ClassBindings;
use Karriere\JsonDecoder\Bindings\FieldBinding;

class GameStateTransformer implements Transformer
{
    public function register(ClassBindings $classBindings)
    {
        $classBindings->register(new FieldBinding('player1', 'player1', PlayerInfo::class));
        $classBindings->register(new FieldBinding('player2', 'player2', PlayerInfo::class));
        $classBindings->register(new FieldBinding('tree', 'tree', Tree::class));
    }

    public function transforms()
    {
        return GameState::class;
    }
}
