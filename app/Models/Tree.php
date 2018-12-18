<?php

namespace App\Models;

use App\Game;
use Illuminate\Support\Facades\Log;

class Tree
{
    public $id;
    public $head;
    public $tail;
    public $orientation;
    public $leftBranch;
    public $rightBranch;

    public $leftBranchLeaf;
    public $rightBranchLeaf;
    public $leftLeafId;
    public $rightLeafId;

    public function __construct()
    {
        $this->leftBranch = new Node;
        $this->rightBranch = new Node;
    }

    public function traverseTree(Node $node, $parentLeaf) {
        if ($node->head === $parentLeaf) {
            $node->leafValue = $node->tail;
        } else {
            $node->leafValue = $node->head;
        }

        if(!is_null($node->child)) {
            $node = $this->traverseTree($node->child, $node->leafValue);
        }
        return $node;
    }

    public function placeNodeInChild(Node &$targetNode, Node &$nodeToPlace, $parentLeaf, $branchDirection) {
        if ($targetNode->head === $parentLeaf) {
            $targetNode->leafValue = $targetNode->tail;
        } else {
            $targetNode->leafValue = $targetNode->head;
        }

        Log::info('Current status of parameters passed to this function when $targetNode->child is null:',
            [
                'targetNode' => $targetNode,
                'nodeToPlace' => $nodeToPlace,
                'parentLeaf' => $parentLeaf,
                'branchDirection' => $branchDirection
            ]);

        if(!is_null($targetNode->child)) {
            Log::info('Parameters to send to placeNodeInChild() when $targetNode->child is not null: ',
                [
                    'targetNode' => $targetNode->child,
                    'nodeToPlace' => $nodeToPlace,
                    'parentLeaf' => $targetNode->leafValue,
                    'branchDirection' => $branchDirection
                ]);
            $this->placeNodeInChild($targetNode->child, $nodeToPlace, $targetNode->leafValue, $branchDirection);
        } else {
            if ($nodeToPlace->head === $nodeToPlace->tail) {
                $nodeToPlace->orientation = "U";
            } elseif (($nodeToPlace->head === $targetNode->leafValue) && ($branchDirection === "R")) {
                $nodeToPlace->orientation = "L";
            } elseif (($nodeToPlace->head === $targetNode->leafValue) && ($branchDirection === "L")) {
                $nodeToPlace->orientation = "R";
            } elseif (($nodeToPlace->tail === $targetNode->leafValue) && ($branchDirection === "R")) {
                $nodeToPlace->orientation = "R";
            } elseif (($nodeToPlace->tail === $targetNode->leafValue) && ($branchDirection === "L")) {
                $nodeToPlace->orientation = "L";
            }
            $targetNode->child = $nodeToPlace;
        }
    }

    public function placeNodeInLeftBranch(Node $node) {
        if ($this->head === $this->tail) {
            $leafValue = $this->tail;
        } else {
            $leafValue = $this->head;
        }

        if ($this->leftBranch->id != null) {
            $this->placeNodeInChild($this->leftBranch, $node, $leafValue, "L");
        } else {
            if ($node->head === $leafValue) {
                $node->orientation = "R";
            } else {
                $node->orientation = "L";
            }
            $this->leftBranch = $node;
        }
    }

    public function placeNodeInRightBranch(Node $node) {
        if ($this->head === $this->tail) {
            $leafValue = $this->tail;
        } else {
            $leafValue = $this->head;
        }

        if ($this->rightBranch->id != null) {
            $this->placeNodeInChild($this->rightBranch, $node, $leafValue, "R");
        } else {
            if ($node->head === $leafValue) {
                $node->orientation = "L";
            } else {
                $node->orientation = "R";
            }
            $this->rightBranch = $node;
        }
    }

    public function getLeftBranchValue(){
        $id = $this->leftBranch->id ?? null;
        if ($id != null) {
            if ($this->head === $this->tail) {
                $leafValue = $this->tail;
            } else {
                $leafValue = $this->head;
            }
            $node = $this->traverseTree($this->leftBranch, $leafValue);
            return $node->leafValue;
        }
        return null;
    }

    public function getRightBranchValue(){
        $id = $this->rightBranch->id ?? null;
        if ($id != null) {
            if ($this->head === $this->tail) {
                $leafValue = $this->head;
            } else {
                $leafValue = $this->tail;
            }
            $node = $this->traverseTree($this->rightBranch, $leafValue);
            return $node->leafValue;
        }
        return null;
    }

    public function getLeftLeafId(){
        $id = $this->leftBranch->id ?? null;
        if ($id != null) {
            if ($this->head === $this->tail) {
                $leafValue = $this->tail;
            } else {
                $leafValue = $this->head;
            }
            $node = $this->traverseTree($this->leftBranch, $leafValue);
            return $node->id;
        }
        return null;
    }

    public function getRightLeafId(){
        $id = $this->rightBranch->id ?? null;
        if ($id != null) {
            if ($this->head === $this->tail) {
                $leafValue = $this->head;
            } else {
                $leafValue = $this->tail;
            }
            $node = $this->traverseTree($this->rightBranch, $leafValue);
            return $node->id;
        }
        return null;
    }
}
