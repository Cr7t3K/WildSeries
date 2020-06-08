<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($e = 0; $e < 6; $e++) {
            for ($a = 0; $a < 7; $a++) {
                for ($i = 0; $i < 6; $i++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->text($maxNbChars = 25));
                    $episode->setNumber($i);
                    $episode->setSynopsis($faker->text);
                    $episode->setSeason($this->getReference('season_' . $e . '_' . $a));
                    $slug = $slugify->generate($episode->getTitle());
                    $episode->setSlug($slug);
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
