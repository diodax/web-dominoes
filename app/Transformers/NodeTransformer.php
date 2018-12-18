<?php

namespace App\Transformers;

use App\Models\Node;
use Illuminate\Support\Collection;
use Karriere\JsonDecoder\Transformer;
use Karriere\JsonDecoder\ClassBindings;
use Karriere\JsonDecoder\Bindings\FieldBinding;

class NodeTransformer implements Transformer
{
    public function register(ClassBindings $classBindings)
    {
        $classBindings->register(new FieldBinding('child', 'child', Node::class));
    }

    public function transforms()
    {
        return Node::class;
    }
}
