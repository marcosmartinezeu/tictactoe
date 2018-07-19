<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Move;
use App\Exception\PayloadNotValidException;
use App\Factory\BoardFactory;
use App\Factory\MoveFactory;
use App\Service\MoveService;
use App\Validator\MatchFinishedValidator;
use App\Validator\MatchIdValidator;
use App\Validator\MoveValidator;
use App\Validator\PayloadValidator;
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
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
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
        $content = json_decode($request->getContent());
        $session = new Session();

        // Get board
        $board = (isset($content->history))
            ? BoardFactory::createBoardFromRequestContent($content)
            : BoardFactory::createNewBoard($session->get('matchId'));

        // Human move
        if (isset($content->nextMove)) {
            // Make human move
            $board->addMove(MoveFactory::loadMoveFromRequestContent($content));

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
     * @param mixed $payload
     * @param Board $board
     */
    private function moveValidation($payload, Board $board)
    {
        // Move validation
        if (isset($content->nextMove)) {
            $moveValidator = new MoveValidator();
            $moveValidator->validate($payload->nextMove, $board);
        }
        // MatchId Validation
        $matchIdValidator = new MatchIdValidator();
        $matchIdValidator->validate($payload->matchId);

        // Match Finished Validation
        $matchFinishedValidator = new MatchFinishedValidator();
        $matchFinishedValidator->validate($payload, $board);

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
