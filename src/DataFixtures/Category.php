<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Category extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('en_US');

        for ($i = 0; $i < 10; ++$i) {
            $category = new \App\Entity\Category();
            $category->setName($this->faker->word());
            $manager->persist($category);
        }

        $manager->flush();
    }
}
