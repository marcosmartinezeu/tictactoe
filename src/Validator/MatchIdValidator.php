<?php

namespace App\Validator;

use App\Exception\MatchIdNotValidException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class MatchIdValidator
{
    /**
     * @var string
     */
    private $message = 'Not valid matchId';

    /**
     * @param string $value
     * @throws MatchIdNotValidException
     */
    public function validate($value)
    {
        $session = new Session();
        if ($value != $session->get('matchId'))
        {
            throw new MatchIdNotValidException($this->message, Response::HTTP_BAD_REQUEST);
        }
    }

}
