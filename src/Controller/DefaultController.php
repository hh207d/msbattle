<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
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
        $opponentPlacements = [];
        $gameState = '';
        $placeableShipsPlayer = [];

        if($gameid)
        {
            $game = $this->getDoctrine()->getRepository(Game::class)->find($gameid);
            $user = $game->getUser();
            $placements = $this->getDoctrine()->getRepository(Placement::class)->findBy(
                ['game' => $game, 'user' => $game->getUser()]
            );

            $gameState = $game->getState();
            $placeableShips = [];
            if($gameState == GameState::STATE_STARTED)
            {
                $placeableShips = $this->getDoctrine()->getRepository(Ship::class)->findBy(
                    ['game' => $game, 'state' => ShipState::STATE_DOCKED, 'user' => $user]
                );
            }




        }
        $someValue = '';
        if($statistics)
        {
            $someValue= 'lol';
        }


        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'games' => $games,
            'gameid' => $gameid,
            'playerPlacements' => $playerPlacements,
            'opponentplacements' => $opponentPlacements,
            'statistics' => $someValue,
            'gameState' => $gameState,
            'placeableShips' => $placeableShips,
        ]);
    }
}
