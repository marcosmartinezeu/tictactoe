<?php

namespace App\Tests;

use App\Entity\Board;
use App\Entity\Move;
use App\Exception\MatchFinishedException;
use App\Exception\MoveNotValidException;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use PHPUnit\Framework\TestCase;

class MoveTest extends TestCase
{
    public function testMoveOnFinishedMatch()
    {
        $this->expectException(MatchFinishedException::class);

        $state = ['o','-','o','x','x','x','o','x','o'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 1);
        $this->generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveNotValidPostion()
    {
        $this->expectException(MoveNotValidException::class);

        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 11);
        $this->generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveNotValidChar()
    {
        $this->expectException(MoveNotValidException::class);

        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create('z', 1);
        $this->generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveValid()
    {
        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 1);
        $board = $this->generateBoardFromState($state)->addMove($newMove);

        $this->assertInstanceOf(Board::class, $board);
    }
    /**
     * @param array $state
     * @return \App\Entity\Board
     */
    private function generateBoardFromState(array $state)
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
