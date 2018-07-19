<?php

namespace App\Validator;

use App\Entity\Board;
use App\Entity\Move;


class MoveValidator
{
    private $message = 'Not valid move';

    /**
     * @param Move $move
     * @param Board $board
     *
     * @return bool
     */
    public function isValid(Move $move, Board $board) : bool
    {
        return ($this->validateChar($move->getChar())
                && $this->validatePosition($move->getPosition(), $board));
    }

    /**
     * @param int $position
     * @param Board $board
     *
     * @return bool
     */
    private function validatePosition($position, Board $board) : bool
    {
        return ($position < Board::BOARD_SIZE && in_array($position, $board->getPossibleMoves()));
    }

    /**
     * @param string $char
     *
     * @return bool
     */
    private function validateChar($char) : bool
    {
        return (in_array($char, [Move::CHAR_COMPUTER_PLAYER, Move::CHAR_HUMAN_PLAYER]));
    }

}