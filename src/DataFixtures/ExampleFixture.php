<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ExampleFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $parameter1 = new Parameter(null, 'Parameter1');
        $manager->persist($parameter1);

        $parameter2 = new Parameter(null, 'Parameter2');
        $manager->persist($parameter2);

        $valueA = new ParameterValue($parameter1, null, 'A');
        $parameter1->addValue($valueA);
        $manager->persist($valueA);

        $valueB = new ParameterValue($parameter1, null, 'B');
        $parameter1->addValue($valueB);
        $manager->persist($valueB);

        $valueC = new ParameterValue($parameter1, null, 'C');
        $parameter1->addValue($valueC);
        $manager->persist($valueC);

        $valueX = new ParameterValue($parameter2, null, 'X');
        $parameter2->addValue($valueX);
        $manager->persist($valueX);

        $valueY = new ParameterValue($parameter2, null, 'Y');
        $parameter2->addValue($valueY);
        $manager->persist($valueY);

        $valueZ = new ParameterValue($parameter2, null, 'Z');
        $parameter2->addValue($valueZ);
        $manager->persist($valueZ);

        $valueA->addProhibitedValue($valueY);
        $valueC->addProhibitedValue($valueZ);

        $manager->flush();
    }

    public static function getGroups() : array
    {
        return ['example'];
    }
}
