<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for ($e = 0; $e < 6; $e++) {
            for ($a = 0; $a < 7; $a++) {
                for ($i = 0; $i < 6; $i++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->title);
                    $episode->setNumber($i);
                    $episode->setSynopsis($faker->text);
                    $episode->setSeason($this->getReference('season_' . $e . '_' . $a));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
