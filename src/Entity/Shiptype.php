<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
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
class Shiptype
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
     * @var string the name of the item
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/name")
     * @Assert\NotNull
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $length;


    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }
}
