<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing")
 */
class Cell
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
    private $xCoordinate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $yCoordinate;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     */
    private $cellstate;

    /**
     * @ORM\ManyToOne(targetEntity=Ship::class, inversedBy="cells")
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="cells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return string
     */
    public function __toString(): string
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
    public function getXCoordinate(): ?int
    {
        return $this->xCoordinate;
    }

    /**
     * @param int $xCoordinate
     */
    public function setXCoordinate(int $xCoordinate): void
    {
        $this->xCoordinate = $xCoordinate;
    }

    /**
     * @return int|null
     */
    public function getYCoordinate(): ?int
    {
        return $this->yCoordinate;
    }

    /**
     * @param int $yCoordinate
     */
    public function setYCoordinate(int $yCoordinate): void
    {
        $this->yCoordinate = $yCoordinate;
    }

    /**
     * @return string|null
     */
    public function getCellstate(): ?string
    {
        return $this->cellstate;
    }

    /**
     * @param string $cellstate
     */
    public function setCellstate(string $cellstate): void
    {
        $this->cellstate = $cellstate;
    }

    /**
     * @return Ship|null
     */
    public function getShip(): ?Ship
    {
        return $this->ship;
    }

    /**
     * @param Ship|null $ship
     * @return $this
     */
    public function setShip(?Ship $ship): self
    {
        $this->ship = $ship;

        return $this;
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
}
