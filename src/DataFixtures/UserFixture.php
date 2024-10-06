<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
       $admin1=new User();
       $admin1->setEmail('admin1@gmail.com');
       $admin1->setRoles(['ROLE_ADMIN']);
       $admin1->setPassword(password_hash('admin1', PASSWORD_DEFAULT));
       $manager->persist($admin1);
       $admin2=new User();
       $admin2->setEmail('admin2@gmail.com');
       $admin2->setRoles(['ROLE_ADMIN']);
       $admin2->setPassword(password_hash('admin2', PASSWORD_DEFAULT));
       $manager->persist($admin2);
       for($i=1;$i<=5;$i++){
           $user=new User();
           $user->setEmail('user'.$i.'@gmail.com');
           $user->setPassword(password_hash('user'.$i, PASSWORD_DEFAULT));
           $manager->persist($user);
       }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
