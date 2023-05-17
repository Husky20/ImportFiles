<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Product extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('en_US');

        for ($i = 0; $i < 10; ++$i) {
            $product = new \App\Entity\Product();
            $product->setName($this->faker->word());
            $product->setNumber($this->faker->randomNumber(8));
            $manager->persist($product);
        }
        $manager->flush();
    }
}
