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

    /**
     * Move constructor.
     * @param string $char
     * @param int $position
     */
    public function __construct($char, $position)
    {
        $this->char = $char;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getChar(): string
    {
        return $this->char;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setChar(string $char) : Move
    {
        $this->char = $char;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position) : Move
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'char' => $this->getChar(),
            'position' => $this->getPosition()
        ];
    }
}