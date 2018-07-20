<?php

namespace App\Factory;


use App\Entity\Board;
use App\Entity\Move;

class BoardFactory
{
    /**
     * @param string $content
     *
     * @return Board
     */
    public static function createBoardFromRequestContent($content) : Board
    {
        $board = new Board(MoveFactory::loadMovesFromHistory($content->history), $content->matchId);

        return $board;
    }

    /**
     *
     * @param string $id
     * @return Board
     */
    public static function createNewBoard($id) : Board
    {
        $board = new Board([], $id);

        return $board;
    }

    /**
     * @param array $state
     * @return \App\Entity\Board
     */
    public static function generateBoardFromState(array $state)
    {
        /** @var Move[] $moves */
        $moves = [];
        foreach ($state as $position => $char)
        {
            if ($char != '-') {
                $moves[] = MoveFactory::create($char, $position);
            }
        }

        $board = BoardFactory::createNewBoard(md5(uniqid()));
        $board->setMoves($moves);

        return $board;
    }

}