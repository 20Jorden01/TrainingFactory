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
        $user->setUsername('test');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Test123'));
        $user->setFirstname('testvnaam');
        $user->setPreprovision('testt');
        $user->setLastname('testanaam');
        $user->setDateofbirth(new DateTime("2000-01-02"));
        $user->setGender('-');
        $user->setEmail('test@test.com');
        $manager->persist($user);

        $manager->flush();
    }
}
