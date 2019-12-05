<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProgramFixtures extends Fixture  implements DependentFixtureInterface
{
    CONST PROGRAMS = [
        'Walking dead' => [
            'summary' => 'The Walking Dead takes place after the onset of a worldwide zombie apocalypse. The zombies, colloquially referred to as "walkers", shamble towards living humans and other creatures to eat them; they are attracted to noise, such as gunshots, and to different scents, e.g. humans. Although it initially seems that only humans that are bitten or scratched by walkers can turn into other walkers, it is revealed early in the series that all living humans carry the pathogen responsible for the mutation. The mutation is activated after the death of the pathogen\'s host, and the only way to permanently kill a walker is to damage its brain or destroy the body, such as by cremating it. ',
        ],
        'The Haunting of Hill House' => [
            'summary' => 'In the summer of 1992, Hugh and Olivia Crain and their children – Steven, Shirley, Theodora, Luke, and Nell – move into Hill House to renovate the mansion in order to sell it and build their own house, designed by Olivia. However, due to unexpected repairs, they have to stay longer, and they begin to experience increasing paranormal phenomena that results in a tragic loss and the family fleeing from the house. Twenty-six years later, the Crain siblings and their estranged father reunite after tragedy strikes again, and they are forced to confront how their time in Hill House had affected each of them. ',
        ]
    ];
    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $program->setTitle($title);
            $program->setSummary($data['summary']);
            $program->setCategory($this->getReference('categorie_0'));
            $this->addReference('program_' . $i,$program);
            $slugify = new Slugify();
            $program->setSlug($slugify->generate($program->getTitle()));
            $manager->persist($program);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies()

    {

        return [CategoryFixtures::class];

    }
}