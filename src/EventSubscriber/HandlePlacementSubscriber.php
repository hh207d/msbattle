<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cell;
use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\User;
use App\Helper\CellState;
use App\Helper\Constant;
use App\Helper\GameState;
use App\Helper\ShipState;
use App\Utils\CoordinatesGetter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandlePlacementSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * HandlePlacementSubscriber constructor.
     * @param Security $security
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
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

    /**
     * @return array|array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handlePlacement', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function handlePlacement(ViewEvent $event)
    {
        $placement = $event->getControllerResult();
        // TODO: try catch?
        $method = $event->getRequest()->getMethod();
        if (!$placement instanceof Placement || Request::METHOD_POST !== $method)
        {
            return;
        }

        $otherShip = $placement->getShip();
        $otherShip->setState(ShipState::STATE_FLOATING);
        $this->entityManager->persist($otherShip);
        $this->entityManager->flush();
        $game = $placement->getGame();

        // TODO: implement COMP placement, as for now the other player makes same placement.. :_(
        $this->doCompPlacement($game, $placement);
        $this->handleGameStateChange($game);
    }

    /**
     * @param Game $game
     * @param Placement $placement
     */
    private function doCompPlacement(Game $game, Placement $placement)
    {
        $enemy = $this->entityManager->getRepository(User::class)->findOneBy(['email' => Constant::COMP_EMAIL]);
        $compPlacement = new Placement();

        $orientation = $placement->getOrientation();
        $x = $placement->getXcoord();
        $y = $placement->getYcoord();
        $compPlacement->setGame($game);
        $compPlacement->setUser($enemy);
        $compPlacement->setXcoord($x);
        $compPlacement->setYcoord($y);
        $compPlacement->setOrientation($orientation);
        $allShips = $game->getShips();
        foreach ($allShips as $otherShip)
        {

            $ownType = $placement->getShip()->getType();
            $compType = $otherShip->getType();
            $shipUser = $otherShip->getUser();
            $compUser = $enemy;

            if ($ownType == $compType && $shipUser == $compUser)
            {
                $compPlacement->setShip($otherShip);
                $otherShip->setState(ShipState::STATE_FLOATING);
                $this->entityManager->persist($otherShip);
                $this->entityManager->flush();

                $coordinatesGetter = new CoordinatesGetter();
                $coordinatesToUpdate = $coordinatesGetter->getPointsToUpdate($placement);
                foreach ($coordinatesToUpdate as $coordinate)
                {
                    $cell = new Cell();
                    $cell->setGame($game);
                    $cell->setUser($compUser);
                    $cell->setShip($otherShip);
                    $cell->setXCoordinate($coordinate[0]);
                    $cell->setYCoordinate($coordinate[1]);
                    $cell->setCellstate(CellState::STATE_PLACED);
                    $this->entityManager->persist($cell);
                }
                $this->entityManager->flush();
            }
        }
        $this->entityManager->persist($compPlacement);
        $this->entityManager->flush();
    }

    /**
     * @param Game $game
     */
    private function handleGameStateChange(Game $game)
    {
        $sem = $this->entityManager->getRepository(Ship::class);
        $dockedShips = $sem->findBy(['state' => ShipState::STATE_DOCKED, 'game' => $game]);
        if (empty($dockedShips))
        {
            $game->setState(GameState::STATE_BATTLE);
            $this->entityManager->persist($game);
            $this->entityManager->flush();
        }
    }

}
