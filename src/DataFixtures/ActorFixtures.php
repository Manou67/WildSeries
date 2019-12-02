<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use  Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager)
    {
        $actor = array();
        $actorsToAdd = 50;
        for ($i = 0; $i < $actorsToAdd; $i++) {
            $faker  =  Faker\Factory::create('fr_FR');
            $faker = $faker->name;
            array_push ($actor, $faker);

        }
        foreach ($actor as $key => $actorname) {
            $actor = new Actor();
            $actor->setName($actorname);
            $actor->addProgram($this->getReference('program_0'));
            $this->addReference('actor_' . $key, $actor);
            $manager->persist($actor);

        }
        $manager->flush();
    }

    public function getDependencies()

    {

        return [ProgramFixtures::class];

    }



}