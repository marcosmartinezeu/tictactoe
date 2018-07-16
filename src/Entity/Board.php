<?php

namespace App\Entity;

/**
 * Class Board
 * @package App\Entity
 */
class Board
{
    const BOARD_SIZE = 9;

    /**
     * @var  Move[]
     */
    private $moves;

    /**
     * @var bool
     */
    private $finished;

    /**
     * @var string
     */
    private $id;

    public function __construct(Array $moves)
    {
        $this->moves = $moves;
        $this->id = '';
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
     *
     * @return Board
     */
    public function setFinished(bool $finished) : Board
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id) : Board
    {
        $this->id = $id;

        return $this;
    }

    public function getState()
    {

    }
}