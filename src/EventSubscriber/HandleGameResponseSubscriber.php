<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandleGameResponseSubscriber implements EventSubscriberInterface
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
     * HandleGameResponseSubscriber constructor.
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
            KernelEvents::VIEW => ['removeCompData', EventPriorities::PRE_SERIALIZE]
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function removeCompData(ViewEvent $event)
    {
        $game = $event->getControllerResult();
        // TODO: try catch?
        $method = $event->getRequest()->getMethod();
        if (!$game instanceof Game || Request::METHOD_POST !== $method) {
            return;
        }

        $ships = $game->getShips();
        $user = $game->getUser();
        foreach ($ships as $ship)
        {
            if($ship->getUser() !== $user)
            {
                $game->removeShip($ship);
            }
        }
        $placements = $game->getPlacements();
        foreach ($placements as $placement)
        {
            if($placement->getUser() !== $user)
            {
                $game->removePlacement($placement);
            }
        }
    }
}
