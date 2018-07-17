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

    /**
     * Board constructor.
     * @param array $moves
     * @param string $id
     */
    public function __construct(Array $moves, string $id)
    {
        $this->moves = $moves;
        $this->id = $id;
    }

    /**
     * @return Move[]
     */
    public function getMoves() : array
    {
        return $this->moves;
    }

    /**
     * @return array
     */
    public function getMovesByPostion() : array
    {
        $movesByPosition = [];

        foreach ($this->getMoves() as $move)
        {
            $movesByPosition[$move->getPosition()] = $move->getChar();
        }

        return $movesByPosition;
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

    /**
     * @return array
     */
    public function getState()
    {
        $state = [];
        $moves = $this->getMovesByPostion();
        for ($position = 0; $position < self::BOARD_SIZE; $position ++)
        {
            $state[] = (isset($moves[$position])) ? $moves[$position] : Move::CHAR_EMPTY;
        }

        return $state;
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        $history = [];
        $historyCounter = 0;
        foreach ($this->getMoves() as $move)
        {
            $history[$historyCounter]['char'] = $move->getChar();
            $history[$historyCounter]['position'] = $move->getPosition();
            $historyCounter ++;
        }
        return $history;
    }

    /**
     * @return bool
     */
    public function getResult()
    {
        return false;
    }
}