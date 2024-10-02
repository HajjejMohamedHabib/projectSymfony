<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profiles=[['url'=>'www.facebook.com/mohamedHajjej','rxSocial'=>'Facebook'],
            ['url'=>'www.google.com/mohamedHajjej','rxSocial'=>'Google'],
            ['url'=>'www.linkedin.com/mohamedHajjej','rxSocial'=>'LinkedIn'],
            ['url'=>'www.twitter.com/mohamedHajjej','rxSocial'=>'Twitter'],
            ];
        for($i=0;$i<count($profiles);$i++){
            $profile=new Profile();
            $profile->setUrl($profiles[$i]['url']);
            $profile->setRxSocial($profiles[$i]['rxSocial']);
            $manager->persist($profile);
        }
        $manager->flush();
    }
}
