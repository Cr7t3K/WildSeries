<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;



class ActorFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $this->addReference('actor_' . $i, $actor);
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween(0,5)));
            $manager->persist($actor);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}