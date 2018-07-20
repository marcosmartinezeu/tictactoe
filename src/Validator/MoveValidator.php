<?php

namespace App\Validator;

use App\Entity\Board;
use App\Entity\Move;


class MoveValidator
{
    private $message = 'Not valid move';

    /**
     * Validate move
     *
     * @param Move $move
     * @param Board $board
     *
     * @return bool
     */
    public function isValid(Move $move, Board $board) : bool
    {
        return ($this->validateChar($move->getChar())
                && $this->validatePosition($move->getPosition(), $board)
                && $this->validateNextPlayer($move->getChar(), $board)
        );
    }

    /**
     * Checks position is valid:
     * - Board values
     * - Board possible moves
     *
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
     * Checks char
     *
     * Human or Computer values
     *
     * @param string $char
     *
     * @return bool
     */
    private function validateChar($char) : bool
    {
        return (in_array($char, [Move::CHAR_COMPUTER_PLAYER, Move::CHAR_HUMAN_PLAYER]));
    }

    /**
     * Validate player order
     *
     * Example:
     * x,o,x,.. next: o
     *
     * @param string $char
     * @return bool
     */
    private function validateNextPlayer($char, Board $board) : bool
    {
        return ($board->getHistory()[count($board->getHistory()) -1] != $char);
    }

}