<?php

namespace Infrastructure\Database\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Domain\User\Data\Model\User;
use Domain\User\Gateway\PasswordHasherInterface;
use Faker\Factory;

class UsersFixture extends Fixture
{
    public function __construct(
        private readonly PasswordHasherInterface $hasher
    ){}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i=0; $i <= 5; $i++) { 

            $user = new User(
                $i,	
                $faker->email(),
                ['ROLE_USER'],
                '',
                new \DateTimeImmutable(),
            );            
            $password = $this->hasher->hashPassword('123456');
            $user->password = $password;
            $manager->persist($user);
        }

        $manager->flush();
    }
}