<?php

namespace TicTacToe\Bundle\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TicTacToeGameBundle:Default:index.html.twig');
    }
}
