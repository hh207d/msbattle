<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(iri="http://schema.org/Thing")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="user")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity=Cell::class, mappedBy="user")
     */
    private $cells;

    /**
     * @ORM\OneToMany(targetEntity=Placement::class, mappedBy="user")
     */
    private $placements;

    /**
     * @ORM\OneToMany(targetEntity=Ship::class, mappedBy="user")
     */
    private $ships;

    /**
     * @ORM\OneToMany(targetEntity=Turn::class, mappedBy="user")
     */
    private $turns;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->cells = new ArrayCollection();
        $this->placements = new ArrayCollection();
        $this->ships = new ArrayCollection();
        $this->turns = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->getEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setUser($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
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
            $cell->setUser($this);
        }

        return $this;
    }

    public function removeCell(Cell $cell): self
    {
        if ($this->cells->contains($cell)) {
            $this->cells->removeElement($cell);
            // set the owning side to null (unless already changed)
            if ($cell->getUser() === $this) {
                $cell->setUser(null);
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
            $placement->setUser($this);
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        if ($this->placements->contains($placement)) {
            $this->placements->removeElement($placement);
            // set the owning side to null (unless already changed)
            if ($placement->getUser() === $this) {
                $placement->setUser(null);
            }
        }

        return $this;
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
            $ship->setUser($this);
        }

        return $this;
    }

    public function removeShip(Ship $ship): self
    {
        if ($this->ships->contains($ship)) {
            $this->ships->removeElement($ship);
            // set the owning side to null (unless already changed)
            if ($ship->getUser() === $this) {
                $ship->setUser(null);
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
            $turn->setUser($this);
        }

        return $this;
    }

    public function removeTurn(Turn $turn): self
    {
        if ($this->turns->contains($turn)) {
            $this->turns->removeElement($turn);
            // set the owning side to null (unless already changed)
            if ($turn->getUser() === $this) {
                $turn->setUser(null);
            }
        }

        return $this;
    }
}
