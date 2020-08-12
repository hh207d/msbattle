<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\Turn;
use App\Helper\GameState;
use App\Helper\ShipState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $gameid = $request->query->get('gameid');
        $statistics = $request->query->get('statistics');
        $games =$this->getDoctrine()->getRepository(Game::class)->findAll();

        $playerPlacements = [];
        $turns = [];
        $gameState = '';
        $placeableShips = [];
        if($gameid)
        {
            $game = $this->getDoctrine()->getRepository(Game::class)->find($gameid);
            $user = $game->getUser();
            $playerPlacements = $this->getDoctrine()->getRepository(Placement::class)->findBy(
                ['game' => $game, 'user' => $game->getUser()]
            );

            $gameState = $game->getState();

            if($gameState == GameState::STATE_STARTED)
            {
                $placeableShips = $this->getDoctrine()->getRepository(Ship::class)->findBy(
                    ['game' => $game, 'state' => ShipState::STATE_DOCKED, 'user' => $user]
                );
            }
            if($gameState == GameState::STATE_BATTLE)
            {
                /** @var Turn[] $turns */
                $turns = $this->getDoctrine()->getRepository(Turn::class)->findBy(['game' => $game]);

            }

        }

        $amountOfGames = 0;
        $startedGames = 0;
        $battlingGames = 0;
        $finishedGames = 0;

        if($statistics)
        {


            $playedGames = [];

            foreach ($games as $game)
            {
                $amountOfGames +=1;
                switch($game->getState())
                {
                    case GameState::STATE_STARTED:
                        $startedGames +=1;
                        break;
                    case GameState::STATE_BATTLE:
                        $battlingGames +=1;
                        break;
                    case GameState::STATE_FINISHED:
                        $finishedGames +=1;
                }
                $player = $game->getUser();
                // TODO: statistics for wins, played, ...
            }

        }


        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'games' => $games,
            'gameid' => $gameid,
            'playerPlacements' => $playerPlacements,
            'amountOfGames' => $amountOfGames,
            'startedGames' => $startedGames,
            'battlingGames' => $battlingGames,
            'finishedGames' => $finishedGames,
            'gameState' => $gameState,
            'placeableShips' => $placeableShips,
            'turns' => $turns,

        ]);
    }
}
