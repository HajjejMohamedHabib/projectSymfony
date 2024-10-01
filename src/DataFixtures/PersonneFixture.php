<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i = 0; $i < 100; $i++) {
            $personne = new Personne();
            $personne->setFirstname($faker->firstName);
            $personne->setLastname($faker->lastName);
            $personne->setAge($faker->numberBetween($min = 1, $max = 99));
            $manager->persist($personne);
        }


        $manager->flush();
    }
}
