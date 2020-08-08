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
}
