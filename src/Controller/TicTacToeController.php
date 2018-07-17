<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Move;
use App\Factory\BoardFactory;
use App\Service\MoveService;
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
     * @return Response
     */
    public function index(Request $request)
    {
        // Create board
        $content = $request->getContent();
        $requestValidator = new RequestValidator();

        // New game
        if (false === $requestValidator->isValid($content))
        {
            $board = BoardFactory::createNewBoard();

            // Random first player
            if (rand(0, 1) === 1)
            {
                // Bot stars
                $moveService = new MoveService($board);
                $nextMove = $moveService->play(Move::CHAR_COMPUTER_PLAYER);
                $board->addMove($nextMove);
            }
            return $this->buildResponse($board, $nextMove);

        }
        else // Show current game
        {
            $board = BoardFactory::createBoardFromRequestContent($content);
        }

        return $this->render('tic-tac-toe/index.html.twig');
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

    /**
     * @param Board $board
     * @param null $nextMove
     *
     * @return JsonResponse
     */
    private function buildResponse(Board $board, $nextMove = null)
    {
        $response = [
            'matchId' => $board->getId(),
            'boardState' => $board->getState(),
            'nextMove' => $nextMove,
            'history' => $board->getMoves(),
            'gameResult' => $board->getResult(),
            'winner' => ($board->getResult() == Board::RESULT_WIN) ? $board->getWinner() : '',
            'customField3' => ''
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
