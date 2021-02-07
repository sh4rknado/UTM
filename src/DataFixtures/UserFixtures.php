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

    public function load(ObjectManager $manager) {

        /**
         * Create Admin
         */
        $user = new User();
        $user->setEmail('admin@gmail.com')
            ->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->addRole('ROLE_ADMIN');
        $user->generateToken();
        $manager->persist($user);

        /**
         * Create User
         */
        $user = new User();
        $user->setEmail('user@gmail.com')
            ->setUsername('user')
            ->setPassword($this->encoder->encodePassword($user, 'user'));
        $user->addRole('ROLE_USER');
        $user->generateToken();
        $manager->persist($user);

        /**
         * Save data in db
         */
        $manager->flush();
    }
}
