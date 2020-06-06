<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($a = 0; $a < 6; $a++) {
            for ($i = 0 ; $i < 7; $i++) {
                $season = new Season();
                $season->setNumber($i);
                $season->setProgram($this->getReference('program_' . $a));
                $this->addReference('season_' . $a . '_' . $i, $season);
                $season->setDescription($faker->text);
                $season->setYear($faker->year);
                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
