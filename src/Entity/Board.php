<?php

namespace App\Entity;

/**
 * Class Board
 * @package App\Entity
 */
class Board
{
    /**
     * @var  Move[]
     */
    private $moves;

    /**
     * @var bool
     */
    private $finished;

    public function __construct(Array $moves)
    {
        $this->moves = $moves;
    }

    /**
     * @return Move[]
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * @param Move[] $moves
     * @return Board
     */
    public function setMoves(array $moves): Board
    {
        $this->moves = $moves;

        return $this;
    }

    /**
     * @param Move $move
     * @return Board
     */
    public function addMove(Move $move) : Board
    {
        $this->moves[] = $move;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     */
    public function setFinished(bool $finished)
    {
        $this->finished = $finished;
    }
}