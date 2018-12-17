<?php

namespace App\Models;

use App\Bone;

class BoneInfo
{
    public $id;
    public $head;
    public $tail;
    public $url;

    public function __construct(Bone $bone)
    {
        $this->id = $bone->head.'.'.$bone->tail;
        $this->head = $bone->head;
        $this->tail = $bone->tail;
        $this->imageUrl = $bone->image_url;
    }
}
