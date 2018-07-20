<?php

namespace App\Validator;

use App\Exception\PayloadNotValidException;
use Symfony\Component\HttpFoundation\Response;

class PayloadValidator
{
    private $message = 'Not valid payload format';

    /**
     * Checks if payload is a valid json and has minimal information
     *
     * @param string $value
     * @throws PayloadNotValidException
     */
    public function validate($value) : void
    {
        $payload = json_decode($value);
        if (json_last_error() == JSON_ERROR_NONE) {
            if ($value != '{}' && (!isset($payload->matchId)
                || !isset($payload->history)
                || !isset($payload->boardState))) {
                throw new PayloadNotValidException($this->message, Response::HTTP_BAD_REQUEST);
            }
        }
        else {
            throw new PayloadNotValidException($this->message, Response::HTTP_BAD_REQUEST);
        }
    }

}