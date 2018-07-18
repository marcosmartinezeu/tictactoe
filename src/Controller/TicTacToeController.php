<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Move;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use App\Service\MoveService;
use App\Validator\RequestValidator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicTacToeController extends Controller
{
    /**
     * @Route("/api/tic-tac-toe/play", methods="GET", name="play")
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
        }
        else // Show current game
        {
            $board = BoardFactory::createBoardFromRequestContent($content);
        }
        return $this->render('tic-tac-toe/index.html.twig', ['matchId' => $board->getId(), 'state' => $board->getState()]);
    }

    /**
     * @Route("/api/tic-tac-toe/play", methods="POST", name="move")
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
        $board = BoardFactory::createBoardFromRequestContent($content);

        // Make human move
        $board->addMove(MoveFactory::loadMoveFromRequestContent($content));

        // Make BOT move
        if ($board->getResult() === false) {
            $moveService = new MoveService($board);
            $nextMove = $moveService->play(Move::CHAR_COMPUTER_PLAYER);
            $board->addMove($nextMove);
        }
        else{
            $nextMove = null;
        }

        return $this->buildResponse($board, $nextMove);

    }

    /**
     * @param Board $board
     * @param null|Move $nextMove
     *
     * @return JsonResponse
     */
    private function buildResponse(Board $board, $nextMove = null)
    {
        $response = [
            'matchId' => $board->getId(),
            'boardState' => $board->getState(),
            'nextMove' => (!is_null($nextMove)) ? $nextMove->toArray() : '',
            'history' => $board->getHistory(),
            'gameResult' => $board->getResult(),
            'winner' => ($board->getResult() == Board::RESULT_WIN) ? $board->getWinner() : '',
            'isFinished' => $board->isFinished()
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
