<?php

namespace TicTacToe\Bundle\GameBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use TicTacToe\Bundle\GameBundle\Manager\GameManager;
use TicTacToe\Bundle\GameBundle\Model\Game;

final class GameCommand extends ContainerAwareCommand
{

    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * @var QuestionHelper
     */
    private $questionHelper;


    protected function configure() : void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('game:start')
            // the short description shown while running "php bin/console list"
            ->setDescription('')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->gameManager = $this->getContainer()->get('tic_tac_toe_game.manager');
        $this->questionHelper = $this->getHelper('question');

        $output->writeln('Welcome to Tic Tac Toe Game');

        $sizeQuestion = new Question('Please enter a number which will be the size for the board ? ', 3);
        $sizeAnswer = $this->questionHelper->ask($input, $output, $sizeQuestion);

        if(!preg_match('/\d/', $sizeAnswer)){
            return;
        }

        $gameModQuestion = new ChoiceQuestion('Please enter the mode you want to play', ['Solo', 'Multiplayer']);
        $gameModAnswer = $this->questionHelper->ask($input, $output, $gameModQuestion);

        $playerName1Question = new Question('Enter name of player 1 ! ', false);
        $playerName1Answer = $this->questionHelper->ask($input, $output, $playerName1Question);
        $playerName2Answer = null;

        if($gameModAnswer === Game::MODE_MULTIPLAYER){
            $playerName2Question = new Question('Enter name of player 2 ! ', false);
            $playerName2Answer = $this->questionHelper->ask($input, $output, $playerName2Question);
        }

        $this->gameManager->initGame((int)$sizeAnswer, $gameModAnswer, $playerName1Answer, $playerName2Answer);
        $currentPlayer = $this->gameManager->game->getPlayer1();

        try {
            while (true) {

                $boardPrint = $this->gameManager->printBoard($this->gameManager->game->getBoard());
                foreach ($boardPrint as $str){
                    $output->writeln($str);
                }

                $coordinates = $this->questionHelper->ask($input, $output, $this->requestCoordinates
                ($currentPlayer->getName()));

                if ($currentPlayer === $this->gameManager->game->getPlayer1()) {

                    $checkTurn = $this->gameManager->playTurn($currentPlayer, $coordinates);

                    if($checkTurn){
                        $currentPlayer = $this->gameManager->game->getPlayer2();
                    }else{
                        $output->writeln('The coordinate are taken or out of range, retry');
                    }

                }else{
                    $checkTurn = $this->gameManager->playTurn($currentPlayer, $coordinates);
                    if($checkTurn){
                        $currentPlayer = $this->gameManager->game->getPlayer1();
                    }else{
                        $output->writeln('The coordinate are taken or out of range, retry');
                    }
                }

                $output->write(sprintf("\033\143"));

                if($this->gameManager->checkWinnerMove()){
                    break;
                }
            }
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln('An unexpeced exception occurred');
            return;
        }

        $output->writeln('The winner is '.$this->gameManager->game->getWinner()->getName());
    }

    /**
     * @return Question
     */
    private function requestCoordinates(string $playerName)
    {
        $coordinatesQuestion = new Question($playerName.', Enter coordinates (sample: 0,0): ', null);
        $coordinatesQuestion->setValidator(
            function ($coordinates) {
                if (!preg_match('/\d,\d/', $coordinates)) {
                    throw new \RuntimeException('Incorrect coordinate, please enter format x,y');
                }
                return $coordinates;
            }
        );

        return $coordinatesQuestion;
    }
}