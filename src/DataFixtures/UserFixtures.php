<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $_encoder) {
        $this->encoder = $_encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@gmail.com')
            ->setRoles(['role_admin'])
            ->setPassword($this->encoder->encodePassword($user, 'admin'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user@gmail.com')
            ->setRoles(['role_user'])
            ->setPassword($this->encoder->encodePassword($user, 'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
