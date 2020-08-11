<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Placement;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandlePlacementSubscriber implements EventSubscriberInterface
{
    private $security;
    private $logger;
    private $entityManager;
    private $requestStack;

    public function __construct(
        Security $security,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    )
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function handlePlacement(ViewEvent $event)
    {
        $placement = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$placement instanceof Placement || Request::METHOD_POST !== $method)
        {
            return;
        }

        // Gedanken zu events:
        // welche Überprüfung ist wann sinnvoll?
        // -> ist einplacement überhaupt möglich gerade? (anderer Spieler ist an der Reihe / SPiel ist nicht im placement modus / ...)
        // -> ist ein placement valide (Schiff nicht richtig plaziert / anderes Schiff belegt Zellen / ..)


        // evaluate placement
        // if not ok return smthg meaningful (from here? how does this even work?)
        // else
        // update ship (state)
        // set Cells
        // update game (activeplayer)
        // let COMP do move ??



    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handlePlacement', EventPriorities::POST_VALIDATE]
        ];
    }
}
