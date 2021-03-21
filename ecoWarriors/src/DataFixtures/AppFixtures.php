<?php
namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
       $this->passwordEncoder=$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123123'
        ));
        $user->setEmail("admin@ec.at");
        $user->setFirstName("Admin");
        $user->setLastName("Admin");
        $user->setRoles(array("ROLE_ADMIN"));
        // $user->setUsername("123123123");

        $manager->persist($user);
        $manager->flush();
   }
}

