<?php

namespace Fixtures;

use App\Entity\Phone\Phone;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class BlogFixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $country_codes = [7, 1, 86, 52, 1905];

        for ($i = 0; $i < 50; $i++) {
            $country_code = $country_codes[array_rand($country_codes)];

            $phone = new Phone(
                $faker->numerify('+' . $country_code . ' (###) ### ####')
            );

            $count = random_int(0, 10);
            for ($j = 0; $j < $count; $j++) {
                $text = $faker->text(200);
                $name = $this->randomName($faker);
                $rating = (!empty($name)) ? $faker->numberBetween(1, 5) : 0;

                $phone->addFeedback($text, $name, $rating);
            }

            $manager->persist($phone);
        }

        $manager->flush();
    }

    private function randomName(\Faker\Generator $faker): ?string
    {
        $rand = (float)rand() / (float)getrandmax();

        return (0.3 < $rand) ? $faker->name : null;
    }
}