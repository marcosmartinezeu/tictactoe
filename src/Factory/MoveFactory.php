<?php

namespace App\Factory;


use App\Entity\Move;

class MoveFactory
{
    /**
     * @param string $char
     * @param int $position
     * @return Move
     */
    public static function create($char, $position) : Move
    {
        return new Move($char, $position);
    }

    /**
     * @param array $boardHistory
     * @return Move[]
     */
    public static function loadMovesFromHistory($boardHistory) : array
    {
        $moves = [];
        foreach ($boardHistory as $move)
        {
            $moves[] = MoveFactory::create($move->char, $move->position);
        }
        return $moves;
    }

    public static function loadMoveFromRequestContent($content)
    {
        return new Move($content->nextMove->char, $content->nextMove->position);
    }
}