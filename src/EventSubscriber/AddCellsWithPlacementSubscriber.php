<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cell;
use App\Entity\Placement;
use App\Utils\CoordinatesGetter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddCellsWithPlacementSubscriber implements EventSubscriberInterface
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

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addCells', EventPriorities::POST_WRITE]
        ];
    }

    public function addCells(ViewEvent $event)
    {
        $placement = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$placement instanceof Placement || Request::METHOD_POST !== $method) {
            return;
        }
        $game = $placement->getGame();
        $ship = $placement->getShip();
        $user = $placement->getUser();

        $coordinatesGetter = new CoordinatesGetter();
        $coordinatesToUpdate = $coordinatesGetter->getPointsToUpdate($placement);
        foreach ($coordinatesToUpdate as $coordinate) {
            $cell = new Cell();
            $cell->setGame($game);
            $cell->setUser($user);
            $cell->setShip($ship);
            $cell->setXCoordinate($coordinate[0]);
            $cell->setYCoordinate($coordinate[1]);
            $cell->setCellstate('STATE_PLACED');
            $this->entityManager->persist($cell);
        }
        $this->entityManager->flush();
    }
}
