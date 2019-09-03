<?php

namespace TicTacToe\Bundle\GameBundle\Model;

final class Game
{

    public const MODE_SOLO = 'Solo';
    public const MODE_MULTIPLAYER = 'Multiplayer';


    /**
     * @var Board
     */
    private $board;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var Player
     */
    private $player1;

    /**
     * @var Player
     */
    private $player2;

    /**
     * @var Player
     */
    private $winner;

    /**
     * @var int
     */
    private $mode;


    public function __construct(int $size, $mode = self::MODE_MULTIPLAYER, $player1Name = 'Player One', $player2Name = 'Player Two')
    {

        $this->board = new Board($size);
        $this->startTime = new \DateTime();
        $this->endTime = new \DateTime();
        $this->player1 = new Player($player1Name, 'X');
        $this->mode = $mode;

        if($this->mode === self::MODE_MULTIPLAYER){
            $this->player2 = new Player($player2Name, 'O');
        }
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    /**
     * @param \DateTime $endTime
     * @return Game
     */
    public function setEndTime(\DateTime $endTime): Game
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return Player
     */
    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    /**
     * @return Player|null
     */
    public function getPlayer2(): ?Player
    {
        return $this->player2;
    }

    /**
     * @return Player
     */
    public function getWinner(): Player
    {
        return $this->winner;
    }

    /**
     * @param Player $winner
     * @return Game
     */
    public function setWinner(Player $winner): Game
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

}