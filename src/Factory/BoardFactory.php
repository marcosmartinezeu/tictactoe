<?php

namespace App\Factory;


use App\Entity\Board;

class BoardFactory
{
    /**
     * @param string $content
     *
     * @return Board
     */
    public static function createBoardFromRequestContent($content)
    {
        $board = new Board(MoveFactory::loadMovesFromHistory($content->history), $content->matchId);

        return $board;
    }

    /**
     *
     * @param string $id
     * @return Board
     */
    public static function createNewBoard($id)
    {
        $board = new Board([], $id);

        return $board;
    }

}