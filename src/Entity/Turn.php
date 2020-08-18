<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Helper\CellState;
use App\Helper\Constant;
use App\Helper\GameState;
use App\Helper\ConstraintMessage;
use App\Helper\ShipState;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/Thing"
 * )
 *
 */
class Turn
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $xcoord;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $ycoord;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     * @var Game $game
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    private $typeOfHitTarget;

    /**
     * @var bool
     */
    private $shipSunken = false;

    private $turnHit;

    /**
     * @return mixed
     */
    public function getTypeOfHitTarget()
    {
        $cells = $this->game->getCells();
        $typeOfHitTarget = Constant::WATER;
        foreach ($cells as $cell)
        {
            if($cell->getUser() === $this->getUser())
            {
                continue;
            }
            if($cell->getXCoordinate() === $this->getXcoord() && $cell->getYCoordinate() === $this->getYcoord())
            {
                $typeOfHitTarget = $cell->getShip()->getType()->getName();
            }
        }
        $this->setTypeOfHitTarget($typeOfHitTarget);
        return $this->typeOfHitTarget;
    }

    /**
     * @param mixed $typeOfHitTarget
     */
    public function setTypeOfHitTarget($typeOfHitTarget): void
    {
        $this->typeOfHitTarget = $typeOfHitTarget;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return strval($this->getId());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $xcoord
     */
    public function setXcoord(int $xcoord): void
    {
        $this->xcoord = $xcoord;
    }

    /**
     * @return int|null
     */
    public function getXcoord(): ?int
    {
        return $this->xcoord;
    }

    /**
     * @param int $ycoord
     */
    public function setYcoord(int $ycoord): void
    {
        $this->ycoord = $ycoord;
    }

    /**
     * @return int|null
     */
    public function getYcoord(): ?int
    {
        return $this->ycoord;
    }

    /**
     * @return Game|null
     */
    public function getGame(): ?Game
    {
        return $this->game;
    }

    /**
     * @param Game|null $game
     * @return $this
     */
    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::GAME_NOT_IN_BATTLE_MODE)
     * @return bool
     */
    public function isGameInBattleMode()
    {
        return $this->getGame()->getState() === GameState::STATE_BATTLE;
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::GAME_NOT_OWNER)
     * @return bool
     */
    public function isUsersGame()
    {
        return $this->getGame()->getUser() === $this->getUser();
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::COORDINATES_ALREADY_BOMBED)
     * @return bool
     */
    public function isBombable()
    {
        $cells = $this->getGame()->getCells();

        foreach ($cells as $cell)
        {
            if($cell->getUser() === $this->getUser())
            {
                continue;
            }
            if($cell->getCellstate() === CellState::STATE_PLACED)
            {
                continue;
            }
            if(
                $cell->getXCoordinate() === $this->getXcoord() &&
                $cell->getYCoordinate() === $this->getYcoord() )
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::TURN_HAS_NO_VALID_COORDS)
     * @return bool
     */
    public function isTurnValid()
    {
        return $this->game->getHeight() > $this->getXcoord() && $this->game->getWidth() > $this->getYcoord();
    }

    /**
     * @return bool
     */
    public function isTurnHit()
    {
        $cells = $this->getGame()->getCells();

        foreach ($cells as $cell)
        {
            if($cell->getCellstate() == CellState::STATE_PLACED)
            {
                if($this->getXcoord() === $cell->getXCoordinate() && $this->getYcoord() === $cell->getYCoordinate())
                {
                    $shipState = $cell->getShip()->getState();
                    if($shipState === ShipState::STATE_SUNK)
                    {
                        $this->setShipSunken(true);
                    }
                    $this->setTurnHit(true);
                    return true;
                }
                return ($this->getXcoord() === $cell->getXCoordinate() && $this->getYcoord() === $cell->getYCoordinate());
            }
        }
        $this->setTurnHit(true);
        return false;

    }

    /**
     * @return mixed
     */
    public function getShipSunken()
    {
        return $this->shipSunken;
    }

    /**
     * @param mixed $shipSunken
     */
    public function setShipSunken($shipSunken): void
    {
        $this->shipSunken = $shipSunken;
    }

    public function isShipSunken()
    {
        return $this->getShipSunken();
    }

    /**
     * @return mixed
     */
    public function getTurnHit()
    {
        return $this->turnHit;
    }

    /**
     * @param mixed $turnHit
     */
    public function setTurnHit($turnHit): void
    {
        $this->turnHit = $turnHit;
    }

}
