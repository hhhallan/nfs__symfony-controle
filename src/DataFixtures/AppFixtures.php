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

        // Randomiser le type
        $types = array("film", "série");
        $randIndex = rand(0, 1);
        $type = $types[$randIndex];

        // Random date
        $timestamp = mt_rand(1, time());
        $randomDate = date("d M Y", $timestamp);

        for ($i = 0; $i < 10; $i++) {
            $objet[$i] = new Objet();
            $objet[$i]
                ->setName("nom du film/série")
                ->setSynopsis("lorem")
                ->setType($type)
                ->setReleaseDate(new \DateTime());

            $manager->persist($objet[$i]);
        }

        $manager->flush();
    }
}
