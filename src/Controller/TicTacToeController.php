<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Move;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use App\Validator\RequestValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicTacToeController extends Controller
{
    /**
     * @Route("/api/tic-tac-toe/play", name="tic_tac_toe")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        // Create board
        $content = $request->getContent();
        $requestValidator = new RequestValidator();

        $board = (false === $requestValidator->isValid($content))
            ? BoardFactory::createNewBoard() // New game
            : BoardFactory::createBoardFromRequestContent($content); // Show current game

        // Ramdom new move
        $board->addMove(MoveFactory::create(Move::CHAR_COMPUTER_PLAYER, 3));

        return $this->buildResponse($board);
        //return $this->render('tic-tac-toe/index.html.twig');
    }

    /**
     * @Route("/api/tic-tac-toe/play", name="tic_tac_toe_move")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function move(Request $request) : JsonResponse
    {
        $content = $request->getContent();
        $requestValidator = new RequestValidator();

        // Create board
        $board = (true === $requestValidator->isValid($content))
            ? BoardFactory::createNewBoard() // New game
            : BoardFactory::createBoardFromRequestContent($content);

        // Make move

        // Update board

        return $this->buildResponse($board);

    }

    private function buildResponse(Board $board)
    {
        $response = [
            'matchId' => $board->getId(),
            'boardState' => $board->getState(),
            'nextMove' => [],
            'history' => $board->getHistory(),
            'gameResult' => $board->getResult(),
            'customField2' => '',
            'customField3' => ''
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
