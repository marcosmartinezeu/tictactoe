<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Move;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use App\Service\MoveService;
use App\Validator\PayloadValidator;
use App\Validator\MatchIdValidator;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @return Response
     */
    public function index() : Response
    {
        $session = new Session();
        $session->set('matchId', md5(uniqid()));
        return $this->render('tic-tac-toe/index.html.twig', ['matchId' => $session->get('matchId')]);
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
        // Payload validation
        $payloadValidator = new PayloadValidator();
        $payloadValidator->validate($request->getContent());

        $payload = json_decode($request->getContent());

        $session = new Session();
        $matchIdValidator = new MatchIdValidator();
        $matchIdValidator->validate($payload->matchId, $session->get('matchId'));

        // Get board
        $board = (isset($payload->history))
            ? BoardFactory::createBoardFromRequestContent($payload)
            : BoardFactory::createNewBoard($session->get('matchId'));

        // Human move
        if (isset($payload->nextMove)) {
            // Make human move
            $board->addMove(MoveFactory::loadMoveFromRequestContent($payload));

            // Make BOT move
            if ($board->getResult() === false) {
                $moveService = new MoveService($board);
                $nextMove = $moveService->play(Move::CHAR_COMPUTER_PLAYER);
                $board->addMove($nextMove);
            }
        }

        return $this->buildResponse($board);
    }

    /**
     * @param Board $board
     *
     * @return JsonResponse
     */
    private function buildResponse(Board $board) : JsonResponse
    {
        $response = [
            'matchId' => $board->getId(),
            'boardState' => $board->getState(),
            'history' => $board->getHistory(),
            'gameResult' => $board->getResult(),
            'winner' => ($board->getResult() == Board::RESULT_WIN) ? $board->getWinner() : '',
            'isFinished' => $board->isFinished()
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
