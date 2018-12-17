<?php

namespace App\Models;

use App\Game;
use App\Bone;
use App\Player;
use Illuminate\Support\Collection;

class GameState
{
    public $id;
    public $player1;
    public $player2;
    public $currentPlayer;
    public $currentTurn;
    public $boneyard;
    public $isGameOver;
    public $tree;

    public function __construct(Game $game)
    {
        $this->id = $game->id;
        $this->isGameOver = false;
        $this->currentTurn = 0;
        $this->player1 = new PlayerInfo(Player::find($game->player_1_id));
        $this->player2 = new PlayerInfo(Player::find($game->player_2_id));
        $this->player1->hand = collect();
        $this->player2->hand = collect();
        $this->tree = new Tree;


        // Get list of Bones and shuffle it
        $bones = collect(Bone::all())->shuffle();

        // Assign 7 bones to each player
        for ($x = 0; $x < 7; $x++) {
            // This is kinda convoluted, but avoids a "Indirect modification of overloaded property" exception
            $bone1 = $bones->pop();
            $this->player1->hand->push($bone1);

            $bone2 = $bones->pop();
            $this->player2->hand->push($bone2);
        }

        // Decide which player goes first, based on their hands
        $hand1 = collect($this->player1->hand);
        $hand2 = collect($this->player2->hand);

        $sortedHand1 = $hand1->sortByDesc('head')->groupBy('head')->map(function (Collection $collection) {
            return $collection->sortByDesc('tail')->groupBy('tail')->ungroup();
        })->ungroup();

        $sortedHand2 = $hand2->sortByDesc('head')->groupBy('head')->map(function (Collection $collection) {
            return $collection->sortByDesc('tail')->groupBy('tail')->ungroup();
        })->ungroup();

        $highestBone1 = $sortedHand1->first();
        $highestBone2 = $sortedHand2->first();

        $firstValue = $highestBone1['head'] + $highestBone1['tail'];
        $secondValue = $highestBone2['head'] + $highestBone2['tail'];

        if ($firstValue > $secondValue) {
            $this->currentPlayer = $this->player1->username;
            $this->player1->validPlays = array($highestBone1['head'].'-'.$highestBone1['tail']);
        } elseif ($firstValue < $secondValue) {
            $this->currentPlayer = $this->player2->username;
            $this->player2->validPlays = array($highestBone2['head'].'-'.$highestBone2['tail']);
        } elseif ($highestBone1['head'] > $highestBone2['head']) {
            $this->currentPlayer = $this->player1->username;
            $this->player1->validPlays = array($highestBone1['head'].'-'.$highestBone1['tail']);
        } else {
            $this->currentPlayer = $this->player2->username;
            $this->player2->validPlays = array($highestBone2['head'].'-'.$highestBone2['tail']);
        }

        // Put the unused bones on the boneyard
        $this->boneyard = $bones;

        // Update the tree values
        $this->tree->leftBranchLeaf = $this->tree->getLeftBranchValue();
        $this->tree->rightBranchLeaf = $this->tree->getRightBranchValue();
        $this->tree->leftLeafId = $this->tree->getLeftLeafId();
        $this->tree->rightLeafId = $this->tree->getRightLeafId();
    }

    public function isMoveValid($username, $move) {
        // Validate move

        // First, check if the username matches the currentPlayer that should be making a move
        if ($username != $this->currentPlayer) {
            return false;
        }

        // Get the player's hand
        if ($this->currentPlayer === $this->player1->username) {
            $hand = $this->player1->hand;
        } else {
            $hand = $this->player2->hand;
        }

        // Validate based on the move type
        switch ($move['type']) {
            case 'DRAW':
                // No need to validate anything else here, so return true
                return true;
                break;
            case 'LAY':
                // Make sure that the bone was originally in the player's hand
                $head = $move['bone']['head'];
                $tail = $move['bone']['tail'];
                $isInHand = $hand->contains(function ($val, $key) use ($head, $tail) {
                    return $val->head == $head && $val->tail == $tail;
                });

                if (!$isInHand) { return false; }

                // Make sure that the branch code is valid
                switch ($move['branchPlayedAt']) {
                    case 'L':
                        // Make sure that the bone can be placed on the selected branch
                        if (($move['bone']['head'] == $this->tree->leftBranchLeaf) ||
                            ($move['bone']['tail'] == $this->tree->leftBranchLeaf)) {
                            return true;
                        } else {
                            return false;
                        }

                    break;
                    case 'C':
                        // Make sure that the bone can be placed on the selected branch
                        if (is_null($this->tree->id)) {
                            // Root tree is empty, so the move is valid
                            return true;
                        } else {
                            // There can't be a piece already on the root tree
                            return false;
                        }
                    break;
                    case 'R':
                        // Make sure that the bone can be placed on the selected branch
                        if (($move['bone']['head'] == $this->tree->rightBranchLeaf) ||
                            ($move['bone']['tail'] == $this->tree->rightBranchLeaf)) {
                            return true;
                        } else {
                            return false;
                        }
                    break;
                    default:
                        // Not a valid recognized branch
                        return false;
                }
                break;
            default:
                // Not a valid recognized move type
                return false;
        }
        // It shouldn't get to this point
        return false;
    }

    public function updateGameState($username, $move) {
        // Get the player's hand

        switch ($move['type']) {
            case 'DRAW':
                // Shuffle the boneyard, pick a card and put it on the player's hand
                $this->boneyard = $this->boneyard->shuffle();
                $bone = $this->boneyard->pop();

                if ($username === $this->player1->username) {
                    $this->player1->hand->push($bone);
                    // Update valid plays here
                } elseif ($username === $this->player2->username) {
                    $this->player2->hand->push($bone);
                    // Update valid plays here
                }
            break;
            case 'LAY':
                //Create the node here
                $node = new Node;
                $node->id = $move['bone']['id'];
                $node->head = $move['bone']['head'];
                $node->tail = $move['bone']['tail'];

                switch ($move['branchPlayedAt']) {
                    case 'L':
                        $this->tree->placeNodeInLeftBranch($node);
                    break;
                    case 'C':
                        $this->tree->id = $node->id;
                        $this->tree->head = $node->head;
                        $this->tree->tail = $node->tail;
                        $this->tree->orientation = "U"; // Root piece is always vertical
                    break;
                    case 'R':
                        $this->tree->placeNodeInRightBranch($node);
                    break;
                    default:
                        // This shouldn't happen
                }

                // Update the tree values
                $this->tree->leftBranchLeaf = $this->tree->getLeftBranchValue();
                $this->tree->rightBranchLeaf = $this->tree->getRightBranchValue();
                $this->tree->leftLeafId = $this->tree->getLeftLeafId();
                $this->tree->rightLeafId = $this->tree->getRightLeafId();

                // Remove the bone from the player's hand
                $head = $node->head;
                $tail = $node->tail;
                if ($username === $this->player1->username) {
                    // Remove from Player1's hand
                    $this->player1->hand = $this->player1->hand->reject(function ($val, $key) use ($head, $tail) {
                        return $val->head == $head && $val->tail == $tail;
                    });

                } else {
                    // Remove from Player2's hand
                    $this->player2->hand = $this->player2->hand->reject(function ($val, $key) use ($head, $tail) {
                        return $val->head == $head && $val->tail == $tail;
                    });
                }

                // Change player turns
                $this->currentTurn = $this->currentTurn + 1;
                $leftBranchLeaf = $this->tree->leftBranchLeaf;
                $rightBranchLeaf = $this->tree->rightBranchLeaf;

                // Update validPlays for the next turn
                if ($this->currentPlayer === $this->player1->username) {
                    $this->currentPlayer = $this->player2->username;
                    // Update validPlays for Player2 and clear it for Player1
                    $this->player1->validPlays = array();
                    $validPlayer2Plays = array();

                    $playableBones2 = $this->player2->hand->filter(function ($val, $key) use ($leftBranchLeaf, $rightBranchLeaf) {
                        return $val->head === $leftBranchLeaf || $val->tail === $rightBranchLeaf ||
                               $val->head === $rightBranchLeaf || $val->tail === $leftBranchLeaf;
                    });

                    foreach ($playableBones2 as $bone) {
                        $validPlayer2Plays[] = $bone['head'].'-'.$bone['tail'];
                    }

                    $this->player2->validPlays = $validPlayer2Plays;

                    // Check if Player1 cleared the win conditions
                    if ($this->player1->hand->isEmpty()) {
                        $this->isGameOver = true;
                        $this->player1->isWinner = true;

                        $game = Game::find($this->id);
                        $game->is_finished = true;
                        $game->save();

                        $player = Player::find($this->player1->id);
                        $player->is_winner = true;
                        $player->save();
                    }
                } else {
                    $this->currentPlayer = $this->player1->username;
                    // Update validPlays for Player1 and clear it for Player2
                    $this->player2->validPlays = array();
                    $validPlayer1Plays = array();

                    $playableBones1 = $this->player1->hand->filter(function ($val, $key) use ($leftBranchLeaf, $rightBranchLeaf) {
                        return $val->head === $leftBranchLeaf || $val->tail === $rightBranchLeaf ||
                               $val->head === $rightBranchLeaf || $val->tail === $leftBranchLeaf;
                    });

                    foreach ($playableBones1 as $bone) {
                        $validPlayer1Plays[] = $bone['head'].'-'.$bone['tail'];
                    }

                    $this->player1->validPlays = $validPlayer1Plays;

                    // Check if Player2 cleared the win conditions
                    if ($this->player2->hand->isEmpty()) {
                        $this->isGameOver = true;
                        $this->player2->isWinner = true;

                        $game = Game::find($this->id);
                        $game->is_finished = true;
                        $game->save();

                        $player = Player::find($this->player2->id);
                        $player->is_winner = true;
                        $player->save();
                    }
                }
            break;
            default:
                // This shouldn't happen
        }

        // Check win conditions here
    }
}
