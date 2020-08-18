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
    /**
     * @var Security
     */
    private $security;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * AddUserToPlacementSubscriber constructor.
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
     */
    public function addUser(ViewEvent $event)
    {
        $placement = $event->getControllerResult();
        // TODO: add try catch?
        $method = $event->getRequest()->getMethod();
        if(!$placement instanceof Placement || Request::METHOD_POST !== $method)
        {
            return;
        }
        $user = $this->security->getUser();
        $placement->setUser($user);

    }
}
