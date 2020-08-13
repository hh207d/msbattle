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


    public function removeCompData(ViewEvent $event)
    {
        $this->logger->log('error', 'WAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAH!');
        $game = $event->getControllerResult();
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

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['removeCompData', EventPriorities::PRE_SERIALIZE]
        ];
    }
}
