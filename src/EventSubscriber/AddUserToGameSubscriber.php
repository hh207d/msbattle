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

    public function addUser(ViewEvent $event)
    {
        $this->logger->log('info','HURRRRRRR!');
        $game = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $this->logger->log('info','DURRRRRRR!');
        if(!$game instanceof Game || Request::METHOD_POST !== $method)
        {
            $this->logger->log('info','MURRRRRRR!');
            return;
        }
        $token = $this->security->getToken();
        $user = $this->security->getUser();
        $this->logger->log('info',"token hier so--> " . $token );
        $this->logger->log('info',"hier so--> " . $user );
        $game->setUser($user);
        $this->logger->log('info','XURRRRRRR!');

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addUser', EventPriorities::PRE_WRITE]
        ];
    }
}
