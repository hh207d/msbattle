<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Turn;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AddUserToTurnSubscriber implements EventSubscriberInterface
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
     * AddUserToTurnSubscriber constructor.
     * @param Security $security
     * @param LoggerInterface $logger
     */
    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    /**
     * @return array|array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addUser', EventPriorities::PRE_VALIDATE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @throws SuspiciousOperationException
     */
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
