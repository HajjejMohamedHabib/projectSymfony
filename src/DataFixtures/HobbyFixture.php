<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hoobies=[
            "Lecture",
            "Écriture",
            "Peinture et dessin",
            "Photographie",
            "Jardinage",
            "Cuisine",
            "Bricolag",
            "Sports",
            "Musique"
        ];
        for($i=0;$i<count($hoobies);$i++){
            $hobby=new Hobby();
            $hobby->setDesignation($hoobies[$i]);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
