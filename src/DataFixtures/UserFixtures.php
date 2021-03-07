<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user_vlad = new User();
        $user_vlad->setEmail('vladimir@scotka.com');
        $user_vlad->setFirstName('Vladimir');
        $user_vlad->setLastName('Scotka');
        $user_vlad->setPassword($this->passwordEncoder->encodePassword($user_vlad, 'vl@d1mir'));
        $user_vlad->setRoles(['ROLE_ADMIN']);
        $manager->persist($user_vlad);
        $manager->flush();

        $scarlett_vlad = new User();
        $scarlett_vlad->setEmail('scarlett.palesova@gmail.com');
        $scarlett_vlad->setFirstName('Scarlett');
        $scarlett_vlad->setLastName('Palesova');
        $scarlett_vlad->setPassword($this->passwordEncoder->encodePassword($scarlett_vlad, 'Skaj@1234'));
        $scarlett_vlad->setRoles(['ROLE_ADMIN']);
        $manager->persist($scarlett_vlad);
        $manager->flush();


    }
}
