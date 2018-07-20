<?php

namespace App\Validator;

use App\Exception\MatchIdNotValidException;

class MatchIdValidator
{
    /**
     * Check payload matchId compare with matchId stored in session
     *
     * @param string $matchId
     * @param string $serverMatchId
     */
    public function validate($matchId, $serverMatchId)
    {
        if ($matchId != $serverMatchId)
        {
            throw new MatchIdNotValidException('MatchId not valid.');
        }
    }

}
