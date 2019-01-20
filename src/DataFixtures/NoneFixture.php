<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class NoneFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->flush();
    }

    public static function getGroups() : array
    {
        return ['none'];
    }
}
