<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cell;
use App\Entity\Game;
use App\Entity\Ship;
use App\Entity\Turn;
use App\Entity\User;
use App\Helper\CellState;
use App\Helper\GameState;
use App\Helper\ShipState;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandleTurnSubscriber implements EventSubscriberInterface
{
    private $security;
    private $logger;
    private $entityManager;

    public function __construct(
        Security $security,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    )
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }


    public function handleTurn(ViewEvent $event)
    {
        $turn = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$turn instanceof Turn || Request::METHOD_POST !== $method)
        {
            return;
        }

        $xCoord = $turn->getXcoord();
        $yCoord = $turn->getYcoord();
        $game = $turn->getGame();
        // TODO: rm magic number..
        $targetPlayer = $this->entityManager->getRepository(User::class)->find(1);

        $targetCell = $this->entityManager->getRepository(Cell::class)->findOneBy(['user' => $targetPlayer,'xCoordinate' => $xCoord, 'yCoordinate' => $yCoord]);

        if($targetCell instanceof Cell)
        {

            $targetCell->setCellstate('HIT');
            // TODO: Update ship -> did it sink?
        }
        else
        {
            $targetCell = new Cell();
            $targetCell->setUser($targetPlayer);
            $targetCell->setCellstate(CellState::STATE_MISSED);
            $targetCell->setGame($game);
            $targetCell->setXCoordinate($xCoord);
            $targetCell->setYCoordinate($yCoord);

        }
        $this->entityManager->persist($targetCell);
        $this->entityManager->flush();

        // TODO: handle enemy turn...
        // TODO: rm duplicated code (HandlePlacementSubscriber)
        $this->handleGameStateChange($game, $targetPlayer);



    }

    private function handleGameStateChange(Game $game, User $targetPlayer)
    {
        $sem = $this->entityManager->getRepository(Ship::class);
        $floatingShips = $sem->findBy(['state' => ShipState::STATE_FLOATING, 'game' => $game, 'user' => $targetPlayer]);

        If(empty($floatingShips))
        {
            $stateToSet = GameState::STATE_FINISHED;
            $game->setState($stateToSet);
            $this->entityManager->persist($game);
            $this->entityManager->flush();
        }


    }



    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleTurn', EventPriorities::POST_WRITE]
        ];
    }


}