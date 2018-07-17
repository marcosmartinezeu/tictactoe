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
        $body = json_decode($content);
        $board = new Board(MoveFactory::loadMovesFromHistory($body->history), $body->matchId);

        return $board;
    }

    /**
     * @return Board
     */
    public static function createNewBoard()
    {
        $board = new Board([], md5(uniqid()));

        return $board;
    }

}