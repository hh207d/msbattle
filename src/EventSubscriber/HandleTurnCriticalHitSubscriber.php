<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cell;
use App\Entity\Turn;
use App\Helper\ShipState;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandleTurnCriticalHitSubscriber implements EventSubscriberInterface
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
     * HandleTurnCriticalHitSubscriber constructor.
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
            KernelEvents::VIEW => ['addIsShipSunken', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function addIsShipSunken(ViewEvent $event)
    {
        /** @var Turn $turn */
        $turn = $event->getControllerResult();
        // TODO: try catch?
        $method = $event->getRequest()->getMethod();
        if (!$turn instanceof Turn || Request::METHOD_POST !== $method)
        {
            return;
        }

        $result = false;
        $cells = $turn->getGame()->getCells();
        /** @var Cell $cell */
        foreach ($cells as $cell) {
            if ($cell->getUser() === $turn->getUser())
            {
                continue;
            }
            if ($cell->getXCoordinate() === $turn->getXcoord() && $cell->getYCoordinate() === $turn->getYcoord())
            {
                $result = $cell->getShip()->getState() === ShipState::STATE_SUNK;
            }
        }
        $turn->setShipSunken($result);
    }
}
