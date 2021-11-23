<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setFirstname('Joe');
        $user->setLastname('Doe');
        $user->setEmail('joe.doe@pm.me');
        $user->setPassword('$2y$13$ppPCGZA/UzdNoBI0qR9bb.EPyIDQ8U/qN.rIEc8FExI8tqsKc2dxC');

        $user->setPhone('+497210000');

        $user->setEmailPublic(true);
        $user->setPhonePublic(false);

        $user->setPronouns('He/Him');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();
    }
}
