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
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="cells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    public function __toString()
    {
        return strval($this->getId());
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setXCoordinate(int $xCoordinate): void
    {
        $this->xCoordinate = $xCoordinate;
    }

    public function getXCoordinate(): ?int
    {
        return $this->xCoordinate;
    }

    public function setYCoordinate(int $yCoordinate): void
    {
        $this->yCoordinate = $yCoordinate;
    }

    public function getYCoordinate(): ?int
    {
        return $this->yCoordinate;
    }

    public function setCellstate(string $cellstate): void
    {
        $this->cellstate = $cellstate;
    }

    public function getCellstate(): ?string
    {
        return $this->cellstate;
    }

    public function getShip(): ?Ship
    {
        return $this->ship;
    }

    public function setShip(?Ship $ship): self
    {
        $this->ship = $ship;

        return $this;
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

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

}