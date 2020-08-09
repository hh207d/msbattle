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
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="turns")
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
}
