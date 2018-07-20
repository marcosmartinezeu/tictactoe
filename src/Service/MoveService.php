<?php


namespace App\Service;


use App\Entity\Board;
use App\Entity\Move;
use App\Factory\MoveFactory;

/**
 * Class MoveService
 *
 * Service to generate computer moves.
 *
 * Game algorithm rules:
 * 1. Try to win
 * 2. Try to avoid opponent victory
 * 3. Random move
 *
 * Examples:
 *
 * [x,-,x]
 * [o,x,o]
 * [-,o,o]
 *
 * Next move (computer, 6) to win
 *
 * [o,o,-]
 * [x,-,-]
 * [-,-,-]
 *
 * Computer cant't win but can avoid opponent victory
 * Next move (computer, 2)
 *
 * @package App\Service
 */
class MoveService
{
    /**
     * @var Board
     */
    private $board;

    /**
     * MoveService constructor.
     *
     * @param Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * @param string $player
     *
     * @return Move
     */
    public function play($player) : Move
    {
        // Can I win?
        $nextMove = $this->getWinnerMove($player);
        if($nextMove instanceof Move)
        {
            return $nextMove;
        }

        // Can I avoid my opponent victory?
        $opponentPlayer = $this->getOppositePlayer($player);
        $nextMove = $this->getWinnerMove($opponentPlayer);
        if($nextMove instanceof Move)
        {
            $nextMove = $nextMove->setChar($player);
            return $nextMove;
        }

        // Standar move
        return $this->getStandardMove($player);
    }

    /**
     * @param string $player
     *
     * @return null|Move
     */
    protected function getWinnerMove($player)
    {
        $winnerMove = null;
        foreach($this->board->getPossibleMoves() as $position)
        {
            if($this->isWinnerMove($position, $player))
            {
                $winnerMove = MoveFactory::create($player, $position);
            }
        }

        return $winnerMove;
    }

    /**
     * If board is empty always stars with center position, else random position
     *
     * @param string $player
     *
     * @return Move
     */
    protected function getStandardMove($player) : Move
    {
        $possibleMoves = $this->board->getPossibleMoves();

        return (count($possibleMoves) == Board::BOARD_SIZE)
            ? MoveFactory::create($player, 4)
            : MoveFactory::create($player, $possibleMoves[array_rand($possibleMoves, 1)]);
    }

    /**
     * @param string $player
     *
     * @return array
     */
    protected function getWinnerPositions($player) : array
    {
        $winnerPositions = [];
        $playerMoves = $this->board->getMovesFromPlayer($player);
        foreach($this->board->getWinnerCombinations() as $winnerCombination)
        {
            $possibleWinnerPositions = array_diff($winnerCombination, $playerMoves);
            if(count($possibleWinnerPositions) === 1)
            {
                $possibleWinnerPosition = array_shift($possibleWinnerPositions);
                if(!in_array($possibleWinnerPosition, $winnerPositions))
                {
                    $winnerPositions[] = $possibleWinnerPosition;
                }
            }
        }

        return $winnerPositions;
    }

    /**
     * @param int $position
     * @param string $player
     *
     * @return bool
     */
    protected function isWinnerMove($position, $player) : bool
    {
        return (in_array($position, $this->getWinnerPositions($player)));
    }

    /**
     * @param string $player
     *
     * @return string
     */
    protected function getOppositePlayer($player) : string
    {
        return ($player == Move::CHAR_COMPUTER_PLAYER)
            ? Move::CHAR_HUMAN_PLAYER
            : Move::CHAR_HUMAN_PLAYER;
    }
}