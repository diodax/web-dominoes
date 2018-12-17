<?php

namespace App\Models;

use App\Game;

class Node
{
    public $id;
    public $head;
    public $tail;
    public $orientation;
    public $child;
    public $leafValue;

    public function __construct()
    {
    }
}
