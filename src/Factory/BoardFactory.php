<?php

namespace App\Factory;


use App\Entity\Board;
use App\Entity\Move;

class BoardFactory
{
    /**
     * @param Move[] $moves
     * @return Board
     */
    public static function create(Array $moves)
    {
        $board = new Board($moves);

        // Check if finished
        // 1 win
        // Move not possible

        return $board;
    }

}