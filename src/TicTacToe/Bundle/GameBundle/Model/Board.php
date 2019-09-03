<?php

namespace TicTacToe\Bundle\GameBundle\Model;

final class Board
{

    /**
     * @var int
     */
    private $size;

    /**
     * @var array
     */
    private $boardLayout;


    public function __construct(int $size)
    {
        $this->size = $size;
        $this->initBoardLayout();
    }


    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getFullBoardSurface(): int
    {
        return $this->size ** 2;
    }

    /**
     * @return array
     */
    public function getBoardLayout(): array
    {
        return $this->boardLayout;
    }

    /**
     * @param int    $row
     * @param int    $column
     * @param string $playerTag
     * @return bool
     */
    public function writeBoardTile(int $row, int $column, string $playerTag) : bool
    {
        if(isset($this->boardLayout[$row][$column]) && $this->boardLayout[$row][$column] === '_'){
            $this->boardLayout[$row][$column] = $playerTag;

            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    private function initBoardLayout() : void
    {
        for ($row = 0; $row < $this->size; $row++) {
            for ($column = 0; $column < $this->size; $column++) {
                $this->boardLayout[$row][$column] = '_';
            }
        }
    }


}