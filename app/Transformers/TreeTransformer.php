<?php

namespace App\Transformers;

use App\Models\GameState;
use App\Models\PlayerInfo;
use App\Models\Tree;
use App\Models\Node;
use Illuminate\Support\Collection;
use Karriere\JsonDecoder\Transformer;
use Karriere\JsonDecoder\ClassBindings;
use Karriere\JsonDecoder\Bindings\FieldBinding;

class TreeTransformer implements Transformer
{
    public function register(ClassBindings $classBindings)
    {
        $classBindings->register(new FieldBinding('leftBranch', 'leftBranch', Node::class));
        $classBindings->register(new FieldBinding('rightBranch', 'rightBranch', Node::class));
    }

    public function transforms()
    {
        return Tree::class;
    }
}
