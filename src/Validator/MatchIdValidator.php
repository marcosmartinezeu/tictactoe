<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Session\Session;

class MatchIdValidator
{
    /**
     * Check payload matchId compare with matchId stored in session
     *
     * @param string $value
     * @return bool
     */
    public function isValid($value) : bool
    {
        $session = new Session();
        return ($value == $session->get('matchId'));
    }

}
