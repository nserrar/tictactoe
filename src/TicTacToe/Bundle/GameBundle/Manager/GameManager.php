<?php

namespace TicTacToe\Bundle\GameBundle\Manager;

use TicTacToe\Bundle\GameBundle\Model\Board;
use TicTacToe\Bundle\GameBundle\Model\Game;
use TicTacToe\Bundle\GameBundle\Model\Player;

class GameManager
{

    /**
     * @var Game
     */
    public $game;

    /**
     * Initialize game with players, size and mode type
     *
     * @param int         $size
     * @param string      $gameMode
     * @param string      $player1Name
     * @param string|null $player2Name
     */
    public function initGame(int $size, string $gameMode, string $player1Name, string $player2Name = null) : void
    {
        $this->game = new Game($size, $gameMode, $player1Name, $player2Name);
    }

    /**
     * Handle player turn, check also if the coordinates are valid
     *
     * @param Player $player
     * @param string $input
     * @return bool
     */
    public function playTurn(Player $player, string $input) : bool
    {
        $board = $this->game->getBoard();
        $rowColumn = explode(',', $input);

        return $board->writeBoardTile($rowColumn[0], $rowColumn[1], $player->getPlayerTag());
    }

    /**
     * Check if there is a winner horizontal, vertical and diagonals
     *
     * @return bool
     */
    public function checkWinnerMove() : bool
    {
        $board = $this->game->getBoard();
        $boardGrid = $board->getBoardLayout();

        for($i = 0; $i < $board->getSize(); $i++)
        {
            for($k = 0; $k < $board->getSize(); $k++){
                if(isset($boardGrid[$i][$k+1],$boardGrid[$i][$k+2]) && $this->checkThreeInRow($boardGrid[$i][$k],
                    $boardGrid[$i][$k+1],
                        $boardGrid[$i][$k+2])){
                    $this->setWinner($boardGrid[$i][$k]);

                    return true;
                }

                if(isset($boardGrid[$k+1][$i], $boardGrid[$k+2][$i]) && $this->checkThreeInRow($boardGrid[$k][$i], $boardGrid[$k+1][$i], $boardGrid[$k+2][$i])){
                    $this->setWinner($boardGrid[$i][$k]);

                    return true;
                }

                if($i === $k){

                    if(isset($boardGrid[$k-1][$i-1], $boardGrid[$k+1][$i+1]) && $this->checkThreeInRow($boardGrid[$k-1][$i-1],
                            $boardGrid[$k][$i],
                            $boardGrid[$k+1][$i+1])){
                        $this->setWinner($boardGrid[$i][$k]);

                        return true;
                    }

                    if(isset($boardGrid[$i-1][$k+1],$boardGrid[$i+1][$k-1]) && $this->checkThreeInRow($boardGrid[$i-1][$k+1], $boardGrid[$i][$k], $boardGrid[$i+1][$k-1])){
                        $this->setWinner($boardGrid[$i][$k]);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Generate board grid into array
     *
     * @param Board $board
     * @return array
     */
    public function printBoard(Board $board) : array
    {
        $boardPrint = [];
        $boardGrid = $board->getBoardLayout();

        for($i=0; $i < $board->getSize(); $i++){
            $str = '';
            for($k=0; $k < $board->getSize(); $k++){
                $str .= $boardGrid[$i][$k];
                if($k !== $board->getSize() - 1){
                    $str .= '|';
                }
            }
            $boardPrint[$i] = $str;
        }
        return $boardPrint;
    }

    /**
     * Used to check if there is three X or O in a row (used to check winner)
     *
     * @param string $val1
     * @param string $val2
     * @param string $val3
     * @return bool
     */
    private function checkThreeInRow(string $val1, string $val2, string $val3) : bool
    {
        if(isset($val1, $val2, $val3) && $val1 !== '_'){
            return ($val1 === $val2) && ($val1 === $val3);
        }

        return false;
    }

    /**
     * Set the winner using winner tag X/O
     *
     * @param string $winnerTag
     */
    private function setWinner(string $winnerTag) : void
    {
        if($winnerTag === 'X'){
            $this->game->setWinner($this->game->getPlayer1());
        }else{
            $this->game->setWinner($this->game->getPlayer2());
        }
    }
}