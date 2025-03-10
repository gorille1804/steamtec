<?php

namespace Infrastructure\Database\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;
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
        
        // Create an array of dates spanning past, present, and future
        $dates = $this->generateDistributedDates();
        
        // Create admin users
        for ($i = 0; $i < 10; $i++) {
            $user = new User(
                UserId::make(),
                $faker->email(),
                ['ROLE_ADMIN'],
                $faker->firstName(),
                $faker->lastName(),
                $faker->phoneNumber(),
                $faker->company(),
                '',
                $this->getRandomDateFromArray($dates, $faker), // Set random date
            );
            
            $password = $this->hasher->hashPassword('123456');
            $user->password = $password;
            $manager->persist($user);
        }
        
        // Create regular users with distributed registration dates
        for ($i = 0; $i < 40; $i++) {
            $user = new User(
                UserId::make(),
                $faker->email(),
                ['ROLE_USER'],
                $faker->firstName(),
                $faker->lastName(),
                $faker->phoneNumber(),
                $faker->company(),
                '',
                $this->getRandomDateFromArray($dates, $faker), // Set random date
            );
            
            $password = $this->hasher->hashPassword('123456');
            $user->password = $password;
            $manager->persist($user);
        }
        
        $manager->flush();
    }
    
    /**
     * Generate a realistic distribution of dates over time
     * with higher concentrations in more recent periods
     */
    private function generateDistributedDates(): array
    {
        $dates = [];
        $now = new \DateTimeImmutable();
        
        // Past dates - Distribute over the past year with increasing frequency
        // Older dates (6-12 months ago) - fewer users
        for ($i = 0; $i < 10; $i++) {
            $daysAgo = mt_rand(180, 365);
            $dates[] = $now->modify("-$daysAgo days");
        }
        
        // Medium past (1-6 months ago) - more users
        for ($i = 0; $i < 15; $i++) {
            $daysAgo = mt_rand(30, 180);
            $dates[] = $now->modify("-$daysAgo days");
        }
        
        // Recent past (last month) - highest concentration
        for ($i = 0; $i < 20; $i++) {
            $daysAgo = mt_rand(1, 30);
            $dates[] = $now->modify("-$daysAgo days");
        }
        
        // Today
        for ($i = 0; $i < 5; $i++) {
            $dates[] = $now;
        }
        
        // Future dates (for testing, if needed)
        for ($i = 0; $i < 5; $i++) {
            $daysAhead = mt_rand(1, 30);
            $dates[] = $now->modify("+$daysAhead days");
        }
        
        return $dates;
    }
    
    /**
     * Get a random date from the array, with probability weighting
     * toward more recent dates to simulate growth
     */
    private function getRandomDateFromArray(array $dates, $faker): \DateTimeImmutable
    {
        // Add some randomness to create a more natural distribution
        if (mt_rand(1, 100) <= 70) {
            // 70% of the time, pick from the latter half of the array (more recent dates)
            $index = $faker->numberBetween(count($dates) / 2, count($dates) - 1);
        } else {
            // 30% of the time, pick from the entire range
            $index = $faker->numberBetween(0, count($dates) - 1);
        }
        
        return $dates[$index];
    }
}