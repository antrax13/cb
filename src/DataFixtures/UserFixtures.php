<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('kosak.p@gmail.com');
        $user->setFirstName('Peter');
        $user->setLastName('Kosak');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'cocktail'));
        $manager->persist($user);

        $user_vlad = new User();
        $user_vlad->setEmail('vladimir@scotka.com');
        $user_vlad->setFirstName('Vladimir');
        $user_vlad->setLastName('Scotka');
        $user_vlad->setPassword($this->passwordEncoder->encodePassword($user, 'vl@d1mir'));
        $manager->persist($user_vlad);
        $manager->flush();
    }
}
