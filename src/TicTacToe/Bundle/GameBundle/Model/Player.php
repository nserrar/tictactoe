<?php

namespace TicTacToe\Bundle\GameBundle\Model;

final class Player
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $playerTag;


    public function __construct(string $name, string $playerTag)
    {
        $this->name = $name;
        $this->playerTag = $playerTag;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Player
     */
    public function setName(string $name): Player
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerTag(): string
    {
        return $this->playerTag;
    }
}