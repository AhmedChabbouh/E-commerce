<?php

namespace App\Entity;
use App\Entity\Product;
use App\Repository\AnimalProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalProductRepository::class)]

class AnimalProduct extends Product
{


    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(type: 'string')]
    private ?string $age = null;


    #[ORM\Column(length: 255)]
    private ?string $breed = null;

    #[ORM\Column(length: 255)]
    private ?string $species = null;



    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(string  $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }
}
