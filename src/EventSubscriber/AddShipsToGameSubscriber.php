<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Game;
use App\Entity\Ship;
use App\Entity\Shiptype;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddShipsToGameSubscriber implements EventSubscriberInterface
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

    public function addShips(ViewEvent $event)
    {
        $game = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$game instanceof Game || Request::METHOD_POST !== $method)
        {
            return;
        }

        $this->entityManager->getRepository(Shiptype::class);
        $shipTypes = $this->entityManager->getRepository(Shiptype::class)->findAll();

        $this->logger->log('info', 'WHAAAAAT?');
        foreach ($shipTypes as $shipType)
        {
            $ship = new Ship();
            $ship->setUser($this->security->getUser());
            $ship->setState('STATE_DOCKED');
            $ship->setType($shipType);
            $ship->setGame($game);
            $this->entityManager->persist($ship);


        }
        $this->entityManager->flush();
        foreach ($shipTypes as $shipType)
        {
            $ship = new Ship();
            $theUser = $this->entityManager->getRepository(User::class)->find(1);
            $this->logger->log('info', 'hier etwa? : ' . $theUser);
            $ship->setUser($theUser);
            $ship->setState('STATE_DOCKED');
            $ship->setType($shipType);
            $ship->setGame($game);
            $this->entityManager->persist($ship);


        }
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addShips', EventPriorities::POST_WRITE]
        ];
    }
}
