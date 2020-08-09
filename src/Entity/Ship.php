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
class Ship
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
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="ships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Shiptype::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Cell::class, mappedBy="ship")
     */
    private $cells;

    /**
     * @ORM\OneToOne(targetEntity=Placement::class, mappedBy="ship", cascade={"persist", "remove"})
     */
    private $placement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
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

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): ?string
    {
        return $this->state;
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

    public function getType(): ?Shiptype
    {
        return $this->type;
    }

    public function setType(?Shiptype $type): self
    {
        $this->type = $type;

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
            $cell->setShip($this);
        }

        return $this;
    }

    public function removeCell(Cell $cell): self
    {
        if ($this->cells->contains($cell)) {
            $this->cells->removeElement($cell);
            // set the owning side to null (unless already changed)
            if ($cell->getShip() === $this) {
                $cell->setShip(null);
            }
        }

        return $this;
    }

    public function getPlacement(): ?Placement
    {
        return $this->placement;
    }

    public function setPlacement(Placement $placement): self
    {
        $this->placement = $placement;

        // set the owning side of the relation if necessary
        if ($placement->getShip() !== $this) {
            $placement->setShip($this);
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
