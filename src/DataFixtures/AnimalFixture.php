<?php

namespace App\DataFixtures;


use App\Entity\AnimalProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\en_US\Animal as AnimalProvider;

class AnimalFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->addProvider(new AnimalProvider($faker));

        $breeds = ['Labrador', 'Persian', 'Siamese', 'Bulldog', 'Golden Retriever', 'Parrot', 'Rabbit'];
        $genders = ['Male', 'Female'];

        for ($i = 0; $i < 30; $i++) {
            $animal = new Animal();
            $animal->setName($faker->firstName);
            $animal->setSpecies($faker->animalName());
            $animal->setBreed($faker->randomElement($breeds));
            $animal->setGender($faker->randomElement($genders));
            $animal->setAge($faker->numberBetween(1, 10));
            $animal->setPrice($faker->randomFloat(2, 50, 1000));
            $animal->setDescription($faker->paragraph);
            $animal->setImageUrl("https://place-puppy.com/300x300?random=" . $i); // or use `https://loremflickr.com/320/240/animal`

            $manager->persist($animal);
        }

        $manager->flush();
    }
}
