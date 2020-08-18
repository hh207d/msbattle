<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Game;
use App\Helper\Constant;
use App\Helper\GameState;
use App\Helper\ShipState;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
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
     * @throws SuspiciousOperationException
     */
    public function removeCompData(ViewEvent $event)
    {
        $game = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$game instanceof Game || !in_array($method, [Request::METHOD_POST, Request::METHOD_GET])) {
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

        $result = Constant::WINNER_NONE;
        if($game->getState() === GameState::STATE_FINISHED)
        {

            $result = Constant::WINNER_COMP;
            $allShips = $game->getShips();
            foreach ($allShips as $ship)
            {
                if($ship->getUser() === $game->getUser() && $ship->getState() === ShipState::STATE_FLOATING)
                {
                    $result = Constant::WINNER_PLAYER;
                    break;
                }
            }
        }
        $game->setWinner($result);

    }

    /**
     * @param ViewEvent $event
     * @throws SuspiciousOperationException
     */
    public function addWinnerData(ViewEvent $event)
    {
        $game = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$game instanceof Game || Request::METHOD_POST !== $method) {
            return;
        }

        $result = 'no winner yet';
        if($game->getState() == GameState::STATE_FINISHED)
        {
            $$result = 'Comp has won';
            $allShips = $game->getShips();
            foreach ($allShips as $ship)
            {
                if($ship->getUser() === $game->getUser() && $ship->getUser() === ShipState::STATE_FLOATING)
                {
                    $$result = 'Player has won';
                    return $result;
                }
            }
        }
        return $result;
    }
}
