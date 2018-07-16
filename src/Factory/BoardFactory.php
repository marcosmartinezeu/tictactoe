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

        /**
         * @todo get possible movements method
         * @todo create aux class with win possible movements
         * @todo create method to get possible win movements
         * @todo create move validaor
         * @todo create render board from moves: an array with 9 elements
         */

        return $board;
    }

}