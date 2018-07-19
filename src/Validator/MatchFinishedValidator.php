<?php

namespace App\Validator;


use App\Entity\Board;
use App\Exception\MatchFinishedException;
use Symfony\Component\HttpFoundation\Response;

class MatchFinishedValidator
{
    private $message = 'Match has finished';

    /**
     * @param mixed $payload
     * @param Board $board
     */
    public function validate($payload, Board $board)
    {
        if (isset($payload->nextMove) && $board->getResult() !== false)
        {
            throw new MatchFinishedException($this->message, Response::HTTP_BAD_REQUEST);
        }
    }
}