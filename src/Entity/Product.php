<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Tests\Fixtures\ConstraintA;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity('number')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(name: 'number', type: 'integer', unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: 'The product number must consist of 8 digits.'
    )]
    private int $number;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number ?? (strlen($number) === 8);
    }
}
