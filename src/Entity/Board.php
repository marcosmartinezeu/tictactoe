<?php

namespace App\Entity;

use App\Exception\MatchFinishedException;
use App\Exception\MoveNotValidException;
use App\Validator\MoveValidator;

/**
 * Class Board
 * @package App\Entity
 */
class Board
{
    const BOARD_SIZE = 9;

    const RESULT_TIE = 'tie';
    const RESULT_WIN = 'win';

    /**
     * @var  Move[]
     */
    private $moves = [];

    /**
     * @var bool
     */
    private $finished = false;

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
        $this->setMoves($moves);
        $this->setId($id);
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
        if ($this->getResult() === false) {
            // Move validation
            $moveValidator = new MoveValidator();
            if ($moveValidator->isValid($move, $this)) {
                $this->moves[] = $move;
            }
            else {
                throw new MoveNotValidException('Not valid move', 400);
            }
        }
        else {
            throw new MatchFinishedException('Match has finished', 400);
        }


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
    public function getState() : array
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
    public function getHistory() : array
    {
        $history = [];
        foreach ($this->getMoves() as $move)
        {
            $history[] = $move->toArray();
        }
        return $history;
    }

    /**
     * @param string $player
     *
     * @return array
     */
    public function getMovesFromPlayer($player) : array
    {
        $playerMoves = [];

        foreach($this->getMoves() as $move)
        {
            if($move->getChar() == $player)
            {
                $playerMoves[] = $move->getPosition();
            }
        }

        return $playerMoves;
    }

    /**
     * @return array
     */
    public function getPossibleMoves() : array
    {
        $posibleMoves = [];
        for($position = 0; $position < Board::BOARD_SIZE; $position++)
        {
            if(!in_array($position, $this->getMovesFromPlayer(Move::CHAR_COMPUTER_PLAYER))
               && !in_array($position, $this->getMovesFromPlayer(Move::CHAR_HUMAN_PLAYER))
            )
            {
                $posibleMoves[] = $position;
            }
        }

        return $posibleMoves;
    }

    /**
     * Returns posible winner combinations
     *
     * @return array
     */
    public function getWinnerCombinations() : array
    {
        return [
            [0, 4, 8],
            [2, 4, 6],
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8],
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8]
        ];

    }


    /**
     * @param string $player
     *
     * @return bool
     */
    protected function isPlayerWinner($player) : bool
    {
        $isWinner = false;
        $playerMoves = $this->getMovesFromPlayer($player);
        foreach($this->getWinnerCombinations() as $winnerCombination)
        {
            if (count(array_diff($winnerCombination, $playerMoves)) === 0)
            {
                $isWinner = true;
                break;
            }
        }
        return $isWinner;
    }

    /**
     * @return null|string
     */
    public function getWinner()
    {
        $winner = null;

        if ($this->isPlayerWinner(Move::CHAR_HUMAN_PLAYER))
        {
            $winner = Move::CHAR_HUMAN_PLAYER;
        }
        elseif ($this->isPlayerWinner(Move::CHAR_COMPUTER_PLAYER))
        {
            $winner = Move::CHAR_COMPUTER_PLAYER;
        }

        return $winner;
    }

    /**
     * @return bool | string
     */
    public function getResult()
    {
        if (count($this->getPossibleMoves()) === 0)
        {
            $result = self:: RESULT_TIE;
            $this->setFinished(true);
        }
        elseif (!is_null($this->getWinner()))
        {
            $result = self::RESULT_WIN;
            $this->setFinished(true);
        }
        else
        {
            $result = false;
            $this->setFinished(false);
        }
        return $result;
    }
}