<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('insturteur1');
        $user->setRoles(["ROLE_INSTRUCTEUR"]);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Test123'));
        $user->setFirstname('test1');
        $user->setPreprovision('test2');
        $user->setLastname('test3');
        $user->setDateofbirth(new DateTime("2000-07-02"));
        $user->setGender('-');
        $user->setEmail('instructeur@test.com');
        $manager->persist($user);

        $manager->flush();
    }
}
