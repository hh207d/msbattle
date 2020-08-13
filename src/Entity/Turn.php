<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Helper\CellState;
use App\Helper\GameState;
use App\Helper\ConstraintMessage;
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


    public function getTypeOfHitTarget()
    {

        $cells = $this->game->getCells();
        // TODO: rm magic string
        $typeOfHitTarget = "WATER";
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



    public function __toString()
    {
        return strval($this->getId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setXcoord(int $xcoord): void
    {
        $this->xcoord = $xcoord;
    }

    public function getXcoord(): ?int
    {
        return $this->xcoord;
    }

    public function setYcoord(int $ycoord): void
    {
        $this->ycoord = $ycoord;
    }

    public function getYcoord(): ?int
    {
        return $this->ycoord;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

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
        return $this->game->getSizeX() > $this->getXcoord() && $this->game->getSizeY() > $this->getYcoord();

    }

    public function isTurnHit()
    {

        $cells = $this->getGame()->getCells();

        foreach ($cells as $cell)
        {
            if($cell->getCellstate() == CellState::STATE_PLACED)
            {
                return ($this->getXcoord() === $cell->getXCoordinate() && $this->getYcoord() === $cell->getYCoordinate());
            }
        }
        return false;

    }




}
