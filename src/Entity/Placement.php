<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Helper\GameState;
use App\Helper\ShipState;
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

    public function __toString()
    {
        return strval($this->getId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getXcoord(): ?int
    {
        return $this->xcoord;
    }

    public function setXcoord(int $xcoord): void
    {
        $this->xcoord = $xcoord;
    }

    public function getYcoord(): ?int
    {
        return $this->ycoord;
    }

    public function setYcoord(int $ycoord): void
    {
        $this->ycoord = $ycoord;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(string $orientation): void
    {
        $this->orientation = $orientation;
    }

    /**
     * @Assert\IsTrue(message="Nope, game is not in placement mode!")
     * @return bool
     */
    public function isGameInPlacementMode()
    {
        return $this->getGame()->getState() === GameState::STATE_STARTED;
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

    /**
     * @Assert\IsTrue(message="Nope, ship is not docked!")
     * @return bool
     */
    public function isShipDocked()
    {
        return $this->getShip()->getState() === ShipState::STATE_DOCKED;
    }

    public function getShip(): ?Ship
    {
        return $this->ship;
    }

    public function setShip(Ship $ship): self
    {
        $this->ship = $ship;

        return $this;
    }

    /**
     * @Assert\IsTrue(message="Nope, it is not your ship!")
     * @return bool
     */
    public function isUsersShip()
    {
        return $this->getShip()->getUser() === $this->getUser();
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
     * @Assert\IsTrue(message="Nope, it is not your game!")
     * @return bool
     */
    public function isUsersGame()
    {
        return $this->getGame()->getUser() === $this->getUser();
    }


}
