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

    /**
     * Ship constructor.
     */
    public function __construct()
    {
        $this->cells = new ArrayCollection();
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
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
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
     * @return Shiptype|null
     */
    public function getType(): ?Shiptype
    {
        return $this->type;
    }

    /**
     * @param Shiptype|null $type
     * @return $this
     */
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

    /**
     * @param Cell $cell
     * @return $this
     */
    public function addCell(Cell $cell): self
    {
        if (!$this->cells->contains($cell)) {
            $this->cells[] = $cell;
            $cell->setShip($this);
        }

        return $this;
    }

    /**
     * @param Cell $cell
     * @return $this
     */
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

    /**
     * @return Placement|null
     */
    public function getPlacement(): ?Placement
    {
        return $this->placement;
    }

    /**
     * @param Placement $placement
     * @return $this
     */
    public function setPlacement(Placement $placement): self
    {
        $this->placement = $placement;

        // set the owning side of the relation if necessary
        if ($placement->getShip() !== $this) {
            $placement->setShip($this);
        }

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
