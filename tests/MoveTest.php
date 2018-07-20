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
        BoardFactory::generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveNotValidPostion()
    {
        $this->expectException(MoveNotValidException::class);

        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 11);
        BoardFactory::generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveNotValidChar()
    {
        $this->expectException(MoveNotValidException::class);

        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create('z', 1);
        BoardFactory::generateBoardFromState($state)->addMove($newMove);
    }

    public function testMoveValid()
    {
        $state = ['o','-','o','x','-','x','o','x','o'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 1);
        $board = BoardFactory::generateBoardFromState($state)->addMove($newMove);

        $this->assertInstanceOf(Board::class, $board);
    }
}
