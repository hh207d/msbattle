<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cell;
use App\Entity\Game;
use App\Entity\Ship;
use App\Entity\Turn;
use App\Entity\User;
use App\Helper\CellState;
use App\Helper\Constant;
use App\Helper\GameState;
use App\Helper\ShipState;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandleTurnSubscriber implements EventSubscriberInterface
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

    /**
     * @param ViewEvent $event
     */
    public function handleTurn(ViewEvent $event)
    {
        $turn = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$turn instanceof Turn || Request::METHOD_POST !== $method)
        {
            return;
        }

        $game = $turn->getGame();
        $player = $game->getUser();
        // TODO: rm magic number..
        $targetPlayer = $this->entityManager->getRepository(User::class)->findOneBy(['email' => Constant::COMP_EMAIL]);

        $this->updateOrCreateCellAfterTurn($turn);
        $this->handleEnemyTurn($player, $targetPlayer, $game);
        $this->handleGameStateChange($game, $targetPlayer);
    }

    /**
     * @param Game $game
     * @param User $targetPlayer
     */
    private function handleGameStateChange(Game $game, User $targetPlayer)
    {
        $sem = $this->entityManager->getRepository(Ship::class);
        $floatingShips = $sem->findBy(['state' => ShipState::STATE_FLOATING, 'game' => $game, 'user' => $targetPlayer]);

        If(empty($floatingShips))
        {
            $stateToSet = GameState::STATE_FINISHED;
            $game->setState($stateToSet);
            $this->entityManager->persist($game);
            $this->entityManager->flush();
        }
    }


    /**
     * @return array|array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleTurn', EventPriorities::POST_WRITE]
        ];
    }


    private function handleEnemyTurn(User $player, User $comp, Game $game)
    {
        $isValidTurn = false;
        while(!$isValidTurn)
        {
            $xCoord = rand(0,Game::DEFAULT_HEIGHT-1);
            $yCoord = rand(0,Game::DEFAULT_WIDTH-1);
            $this->logger->log('error', 'xCoord: '. $xCoord);
            $this->logger->log('error', 'yCoord: '. $yCoord);
            $targetCell = $this->entityManager->getRepository(Cell::class)->findOneBy(['user' => $player,'xCoordinate' => $xCoord, 'yCoordinate' => $yCoord]);

            if($targetCell)
            {
                if($targetCell->getCellstate() === CellState::STATE_PLACED)
                {
                    $isValidTurn = true;
                }
            }
        }

        $enemyTurn = new Turn();
        $enemyTurn->setUser($comp);
        $enemyTurn->setXcoord($xCoord);
        $enemyTurn->setYcoord($yCoord);
        $enemyTurn->setGame($game);

        $this->entityManager->persist($enemyTurn);
        $this->entityManager->flush();

        $this->updateOrCreateCellAfterTurn($enemyTurn);
    }

    private function updateOrCreateCellAfterTurn(Turn $turn)
    {
        // turn: 'user' ist der der grad den Turn gemacht hat
        // cell: 'user' ist der, auf dessen Ozean die Bombe platziert wird

        $game = $turn->getGame();
        $isEnemyTurn = $turn->getUser()->getEmail() == Constant::COMP_EMAIL;
        $player = $turn->getGame()->getUser();
        $comp = $this->entityManager->getRepository(User::class)->findOneBy(['email' => Constant::COMP_EMAIL]);
        $activePlayer = $isEnemyTurn ? $comp : $player;
        $targetPlayer = $isEnemyTurn ? $player : $comp;

        $xCoord = $turn->getXcoord();
        $yCoord = $turn->getYcoord();

        $targetCell = $this->entityManager->getRepository(Cell::class)->findOneBy(['game' => $game, 'user' => $targetPlayer,'xCoordinate' => $xCoord, 'yCoordinate' => $yCoord]);

        if($targetCell instanceof Cell)
        {
            $targetCell->setCellstate(CellState::STATE_HIT);

        }
        else
        {
            $targetCell = new Cell();
            $targetCell->setUser($activePlayer);
            $targetCell->setCellstate(CellState::STATE_MISSED);
            $targetCell->setGame($game);
            $targetCell->setXCoordinate($xCoord);
            $targetCell->setYCoordinate($yCoord);

        }
        $this->entityManager->persist($targetCell);
        $this->logger->log('error', 'targetCell->getCellstate()');
        $this->logger->log('error', $targetCell->getCellstate());
        $this->entityManager->flush();

        if($targetCell && $targetCell->getShip() != null)
        {
            $this->checkAndUpdateShipState($targetCell);
            // TODO: Update ship -> did it sink?
        }



    }

     private function checkAndUpdateShipState(Cell $cell)
     {
         $this->logger->log('Error', 'bin da..');
         $ship = $cell->getShip();
         $allCellsForShip = $ship->getCells();
         $isSunk = true;
         foreach ($allCellsForShip as $shipCell)
         {
             $this->logger->log('Error', 'shipCell:');
             $this->logger->log('Error', 'getCellstate: ' . $shipCell->getCellstate());
             $this->logger->log('Error', 'getId: ' . $shipCell->getId());
             if($shipCell->getCellstate() !== CellState::STATE_HIT)
             {
                 $this->logger->log('Error', 'if..');
                 $isSunk = false;
             }
         }
         if($isSunk)
         {

             $this->logger->log('Error', 'isSunk..');
             $ship->setState(ShipState::STATE_SUNK);
             $this->entityManager->persist($ship);
             $this->entityManager->flush();
         }

     }
}
