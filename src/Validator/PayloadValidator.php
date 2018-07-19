<?php

namespace App\Validator;

use App\Exception\PayloadNotValidException;
use Symfony\Component\HttpFoundation\Response;

class PayloadValidator
{
    private $message = 'Not valid payload format';

    /**
     * @param string $value
     * @throws PayloadNotValidException
     */
    public function validate($value)
    {
        json_decode($value);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new PayloadNotValidException($this->message, Response::HTTP_BAD_REQUEST);
        }
    }

}