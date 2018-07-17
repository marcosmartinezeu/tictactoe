<?php

namespace App\Entity;

/**
 * Class Move
 * @package App\Entity
 */
class Move
{
    const CHAR_EMPTY = '-';
    const CHAR_HUMAN_PLAYER = 'o';
    const CHAR_COMPUTER_PLAYER = 'x';

    /**
     * @var string
     */
    private $char = self::CHAR_EMPTY;

    /**
     * @var int
     */
    private $position;
}