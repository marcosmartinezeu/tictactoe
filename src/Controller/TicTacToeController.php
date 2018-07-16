<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicTacToeController extends Controller
{
    /**
     * @Route("/game", name="tic_tac_toe")
     */
    public function index()
    {
        return $this->render('tic-tac-toe/index.html.twig');
    }

    /**
     * @Route("/game/move", name="play")
     */
    public function move()
    {
    }
}
