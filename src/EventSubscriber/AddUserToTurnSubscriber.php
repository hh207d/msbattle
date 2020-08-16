<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Turn;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddUserToTurnSubscriber implements EventSubscriberInterface
{
    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addUser', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function addUser(ViewEvent $event)
    {
        $turn = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$turn instanceof Turn || Request::METHOD_POST !== $method)
        {
            return;
        }
        $user = $this->security->getUser();
        $turn->setUser($user);
    }
}
