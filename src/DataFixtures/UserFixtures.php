<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const AvailableUserNames = [
        'Charlie', 'Riley', 'David', 'Stevie', 'Toby', 'Karel',
        'Kai', 'Jordan', 'Jules', 'Harper', 'Glenn', 'Gray',
        'Elliott', 'Lenka', 'Devin', 'Delta', 'Petra', 'Michaela',
        'Cameron', 'Campbell', 'Brett', 'Bailey', 'Avery', 'Aubrey',
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


        $manager->flush();
    }
}
