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
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Shiptype::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;


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
}
