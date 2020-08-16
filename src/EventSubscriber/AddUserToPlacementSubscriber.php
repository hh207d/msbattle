<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Placement;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddUserToPlacementSubscriber implements EventSubscriberInterface
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
        $placement = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$placement instanceof Placement || Request::METHOD_POST !== $method)
        {
            return;
        }
        $user = $this->security->getUser();
        $placement->setUser($user);

    }
}
