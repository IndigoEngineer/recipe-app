<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity('name')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'The name cannot be empty')]
    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'the name must be at least {{ limit }} characters long',
        maxMessage: 'The first name cannot be longer than {{ limit }} characters',
    )]
//    #[Assert\Unique(message: 'This name is already used')]
    private ?string $name = null;


    #[Assert\LessThan(
        value: 200,
    )]
    #[Assert\NotNull(message: 'The price cannot be empty')]
    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private ?float $price = null;

    #[Assert\NotNull(message: 'The date cannot be empty')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString():string
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }
}
