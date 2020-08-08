<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
class Player
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
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Ship::class, mappedBy="player")
     */
    private $ships;

    /**
     * @ORM\OneToMany(targetEntity=Placement::class, mappedBy="player")
     */
    private $placements;

    /**
     * @ORM\OneToMany(targetEntity=Cell::class, mappedBy="player")
     */
    private $cells;

    /**
     * @ORM\OneToMany(targetEntity=Turn::class, mappedBy="player")
     */
    private $turns;

    public function __construct()
    {
        $this->ships = new ArrayCollection();
        $this->placements = new ArrayCollection();
        $this->cells = new ArrayCollection();
        $this->turns = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return Collection|Ship[]
     */
    public function getShips(): Collection
    {
        return $this->ships;
    }

    public function addShip(Ship $ship): self
    {
        if (!$this->ships->contains($ship)) {
            $this->ships[] = $ship;
            $ship->setPlayer($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): self
    {
        if ($this->ships->contains($ship)) {
            $this->ships->removeElement($ship);
            // set the owning side to null (unless already changed)
            if ($ship->getPlayer() === $this) {
                $ship->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Placement[]
     */
    public function getPlacements(): Collection
    {
        return $this->placements;
    }

    public function addPlacement(Placement $placement): self
    {
        if (!$this->placements->contains($placement)) {
            $this->placements[] = $placement;
            $placement->setPlayer($this);
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        if ($this->placements->contains($placement)) {
            $this->placements->removeElement($placement);
            // set the owning side to null (unless already changed)
            if ($placement->getPlayer() === $this) {
                $placement->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cell[]
     */
    public function getCells(): Collection
    {
        return $this->cells;
    }

    public function addCell(Cell $cell): self
    {
        if (!$this->cells->contains($cell)) {
            $this->cells[] = $cell;
            $cell->setPlayer($this);
        }

        return $this;
    }

    public function removeCell(Cell $cell): self
    {
        if ($this->cells->contains($cell)) {
            $this->cells->removeElement($cell);
            // set the owning side to null (unless already changed)
            if ($cell->getPlayer() === $this) {
                $cell->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Turn[]
     */
    public function getTurns(): Collection
    {
        return $this->turns;
    }

    public function addTurn(Turn $turn): self
    {
        if (!$this->turns->contains($turn)) {
            $this->turns[] = $turn;
            $turn->setPlayer($this);
        }

        return $this;
    }

    public function removeTurn(Turn $turn): self
    {
        if ($this->turns->contains($turn)) {
            $this->turns->removeElement($turn);
            // set the owning side to null (unless already changed)
            if ($turn->getPlayer() === $this) {
                $turn->setPlayer(null);
            }
        }

        return $this;
    }
}
