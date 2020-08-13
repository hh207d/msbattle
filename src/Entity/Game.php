<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Game type represents things which are games. These are typically rule-governed recreational activities, e.g. role-playing games in which players assume the role of characters in a fictional setting.
 *
 * @see http://schema.org/Game Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Game")
 */
class Game
{
    const DEFAULT_X_SIZE = 8;
    const DEFAULT_Y_SIZE = 8;

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
    private $sizeX;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $sizeY;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=Ship::class, mappedBy="game")
     */
    private $ships;

    /**
     * @ORM\OneToMany(targetEntity=Placement::class, mappedBy="game")
     */
    private $placements;

    /**
     * @ORM\OneToMany(targetEntity=Turn::class, mappedBy="game")
     */
    private $turns;

    /**
     * @ORM\OneToMany(targetEntity=Cell::class, mappedBy="game")
     */
    private $cells;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="games")

     */
    private $user;

    public function __construct()
    {
        $this->ships = new ArrayCollection();
        $this->placements = new ArrayCollection();
        $this->turns = new ArrayCollection();
        $this->cells = new ArrayCollection();
    }

    public function __toString()
    {
        return strval($this->getId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setSizeX(int $sizeX): void
    {
        $this->sizeX = $sizeX;
    }

    public function getSizeX(): ?int
    {
        return $this->sizeX;
    }

    public function setSizeY(int $sizeY): void
    {
        $this->sizeY = $sizeY;
    }

    public function getSizeY(): ?int
    {
        return $this->sizeY;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): ?string
    {
        return $this->state;
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
            $ship->setGame($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): self
    {
        if ($this->ships->contains($ship)) {
            $this->ships->removeElement($ship);
            // set the owning side to null (unless already changed)
            if ($ship->getGame() === $this) {
                $ship->setGame(null);
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
            $placement->setGame($this);
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        if ($this->placements->contains($placement)) {
            $this->placements->removeElement($placement);
            // set the owning side to null (unless already changed)
            if ($placement->getGame() === $this) {
                $placement->setGame(null);
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
            $turn->setGame($this);
        }

        return $this;
    }

    public function removeTurn(Turn $turn): self
    {
        if ($this->turns->contains($turn)) {
            $this->turns->removeElement($turn);
            // set the owning side to null (unless already changed)
            if ($turn->getGame() === $this) {
                $turn->setGame(null);
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
            $cell->setGame($this);
        }

        return $this;
    }

    public function removeCell(Cell $cell): self
    {
        if ($this->cells->contains($cell)) {
            $this->cells->removeElement($cell);
            // set the owning side to null (unless already changed)
            if ($cell->getGame() === $this) {
                $cell->setGame(null);
            }
        }

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
}
