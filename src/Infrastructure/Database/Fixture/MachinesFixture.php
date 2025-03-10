<?php

namespace Infrastructure\Database\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Faker\Factory;

class MachinesFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $dates = $this->generateDistributedDates();

        // Create 30 Machine entities
        for ($i = 0; $i < 30; $i++) {
            $createdAt = $this->getRandomDateFromArray($dates, $faker);

            // Optionally assign updatedAt (50% chance)
            $updatedAt = null;
            if (mt_rand(0, 1)) {
                // Use createdAt directly as DateTimeImmutable is immutable
                $createdAtDateTime = \DateTime::createFromImmutable($createdAt);
                $updatedAt = $faker->dateTimeBetween($createdAtDateTime, 'now');
            }

            $machine = new Machine(
                MachineId::make(),
                $faker->bothify('???-####'),
                $faker->word(),
                $faker->company(),
                $faker->numberBetween(50, 500),
                null,
                $createdAt,
                $updatedAt
            );

            $manager->persist($machine);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['machines'];
    }

    /**
     * Generate a realistic distribution of dates over time.
     */
    private function generateDistributedDates(): array
    {
        $dates = [];
        $now = new \DateTimeImmutable();

        // Past dates: Older dates (6-12 months ago)
        for ($i = 0; $i < 10; $i++) {
            $daysAgo = mt_rand(180, 365);
            $dates[] = $now->modify("-$daysAgo days");
        }

        // Medium past (1-6 months ago)
        for ($i = 0; $i < 15; $i++) {
            $daysAgo = mt_rand(30, 180);
            $dates[] = $now->modify("-$daysAgo days");
        }

        // Recent past (last month)
        for ($i = 0; $i < 20; $i++) {
            $daysAgo = mt_rand(1, 30);
            $dates[] = $now->modify("-$daysAgo days");
        }

        // Today
        for ($i = 0; $i < 5; $i++) {
            $dates[] = $now;
        }

        // Remove future dates as they're causing the issue
        // Future dates would be after "now" which breaks dateTimeBetween()

        return $dates;
    }

    /**
     * Randomly select a date from the array with a bias toward more recent dates.
     */
    private function getRandomDateFromArray(array $dates, $faker): \DateTimeImmutable
    {
        if (mt_rand(1, 100) <= 70) {
            $index = $faker->numberBetween((int)(count($dates) / 2), count($dates) - 1);
        } else {
            $index = $faker->numberBetween(0, count($dates) - 1);
        }

        return $dates[$index];
    }
}