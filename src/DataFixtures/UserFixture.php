<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("usuario")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$l7oo326DN9pbiCj+c5F80A$wuldpNGq0GtKbSeedWdjqi90+9Azu+1aaziJw83nkN0');

        $manager->flush();
    }
}