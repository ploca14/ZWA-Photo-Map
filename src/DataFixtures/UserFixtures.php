<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const AvailableUserNames = [
        'Riley', 'David', 'Stevie', 'Toby', 'Karel',
        'Oliver', 'Ash', 'Andy', 'Alex', 'Michal', 'Petr'
    ];

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::AvailableUserNames as $key => $name) {
            $user = new User();

            $user->setEmail(strtolower($name) . "@fel.cvut.cz");
            $user->setName($name);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'heslo123'
            ));

            $manager->persist($user);
            $this->addReference('user-' . strtolower($name), $user);
        }

        $user = new User();

        $user->setEmail('charlie@fel.cvut.cz');
        $user->setName('Charlie');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'heslo123'
        ));
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $this->addReference('user-charlie', $user);

        $manager->flush();
    }
}
