<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Helper\GameState;
use App\Helper\Orientation;
use App\Helper\ShipState;
use App\Helper\ConstraintMessage;
use App\Validator\PlacementInsideOcean;
use App\Validator\PlacementNoCollision;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing")
 * @PlacementNoCollision()
 * @PlacementInsideOcean()
 */
class Placement
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
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     */
    private $orientation;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="placements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\OneToOne(targetEntity=Ship::class, inversedBy="placement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="placements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * @return int|null
     */
    public function getXcoord(): ?int
    {
        return $this->xcoord;
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
    public function getYcoord(): ?int
    {
        return $this->ycoord;
    }

    /**
     * @param int $ycoord
     */
    public function setYcoord(int $ycoord): void
    {
        $this->ycoord = $ycoord;
    }

    /**
     * @return string|null
     */
    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    /**
     * @param string $orientation
     */
    public function setOrientation(string $orientation): void
    {
        $this->orientation = $orientation;
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::GAME_NOT_IN_PLACEMENT_MODE)
     * @return bool
     */
    public function isGameInPlacementMode()
    {
        return $this->getGame()->getState() === GameState::STATE_STARTED;
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
     * @Assert\IsTrue(message=ConstraintMessage::SHIP_IS_NOT_DOCKED)
     * @return bool
     */
    public function isShipDocked()
    {
        return $this->getShip()->getState() === ShipState::STATE_DOCKED;
    }

    /**
     * @return Ship|null
     */
    public function getShip(): ?Ship
    {
        return $this->ship;
    }

    /**
     * @param Ship $ship
     * @return $this
     */
    public function setShip(Ship $ship): self
    {
        $this->ship = $ship;

        return $this;
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::SHIP_IS_NOT_YOURS)
     * @return bool
     */
    public function isUsersShip()
    {
        return $this->getShip()->getUser() === $this->getUser();
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
     * @Assert\IsTrue(message=ConstraintMessage::GAME_NOT_OWNER)
     * @return bool
     */
    public function isUsersGame()
    {
        return $this->getGame()->getUser() === $this->getUser();
    }

    /**
     * @Assert\IsTrue(message=ConstraintMessage::NO_VALID_ORIENTATION)
     * @return bool
     */
    public function isOrientationValid()
    {
        return in_array($this->getOrientation(), [Orientation::HORIZONTAL, Orientation::VERTICAL]);
    }
}
