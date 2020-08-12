<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Game;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddUserToGameSubscriber implements EventSubscriberInterface
{
    private $security;
    private $logger;

    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function addUser(ViewEvent $event)
    {
        $game = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$game instanceof Game || Request::METHOD_POST !== $method) {
            return;
        }
        $user = $this->security->getUser();
        $game->setUser($user);

    }
}
