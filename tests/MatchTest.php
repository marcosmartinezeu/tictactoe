<?php

namespace App\Tests;

use App\Entity\Board;
use App\Entity\Move;
use App\Exception\MatchFinishedException;
use App\Exception\MoveNotValidException;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    public function testMatchWinHuman()
    {
        $state = ['o','-','o','x','-','x','o','x','-'];
        $newMove = MoveFactory::create(Move::CHAR_HUMAN_PLAYER, 1);
        $board = BoardFactory::generateBoardFromState($state)->addMove($newMove);
        $board->getResult();
        $this->assertTrue($board->isFinished());
        $this->assertEquals(Move::CHAR_HUMAN_PLAYER, $board->getWinner());
    }

    public function testMatchWinComputer()
    {
        $state = ['x','-','x','o','-','o','x','o','-'];
        $newMove = MoveFactory::create(Move::CHAR_COMPUTER_PLAYER, 1);
        $board = BoardFactory::generateBoardFromState($state)->addMove($newMove);
        $board->getResult();
        $this->assertTrue($board->isFinished());
        $this->assertEquals(Move::CHAR_COMPUTER_PLAYER, $board->getWinner());
    }

    public function testMatchTie()
    {
        $state = ['o','x','o','o','x','-','x','o','o'];
        $newMove = MoveFactory::create(Move::CHAR_COMPUTER_PLAYER, 5);
        $board = BoardFactory::generateBoardFromState($state)->addMove($newMove);
        $this->assertEquals(Board::RESULT_TIE, $board->getResult());
    }
}
