<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin=new User();
        $admin->setUsername('admin')
            ->setEmail('admin@unilink.pl')
            ->setRoles(array("ROLE_USER","ROLE_ADMIN"))
        ;
        $password = $this->encoder->encodePassword($admin, 'adminToMoc1234');
        $admin->setPassword($password);

        $user=new User();
        $user->setUsername('user_one')
            ->setEmail('user@unilink.pl')
            ->setRoles(array("ROLE_USER"));
        $pass=$this->encoder->encodePassword($user,'MocneHaslo0987');
        $user->setPassword($pass);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->flush();
    }
}
