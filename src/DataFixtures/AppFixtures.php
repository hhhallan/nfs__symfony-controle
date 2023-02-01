<?php

namespace App\DataFixtures;

use App\Entity\Objet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $objet = Array();

        for ($i = 0; $i < 10; $i++) {
            $objet[$i] = new Objet();
            $objet[$i]
                ->setName($faker->name)
                ->setSynopsis($faker->realText(250))
                ->setType($faker->randomElement(["film", "série"]))
                ->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'));

            $manager->persist($objet[$i]);
        }
        $manager->flush();
    }
}
