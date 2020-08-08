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

    public function setOrientation(string $orientation): void
    {
        $this->orientation = $orientation;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }
}
