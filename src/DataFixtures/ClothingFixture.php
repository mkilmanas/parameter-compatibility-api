<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ClothingFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $params = $this->createParameters($manager, ['Size', 'Color', 'NeckType', 'Material']);

        $values['Size'] = $this->createValues($manager, $params['Size'], ['XS', 'S', 'M', 'L', 'XL']);
        $values['Color'] = $this->createValues($manager, $params['Color'], ['Black', 'White', 'Navy', 'Red']);
        $values['NeckType'] = $this->createValues($manager, $params['NeckType'], ['V', 'Turtle', 'Crew']);
        $values['Material'] = $this->createValues($manager, $params['Material'], ['Linen', 'Wool', 'Cotton', 'Leather']);

        $prohibited = [
            'Size-XS' => [
                'Color-White',
                'NeckType-V',
                'Material-Leather',
            ],
            'Size-S' => [
                'Color-White',
            ],
            'Size-XL' => [
                'Color-Navy',
                'NeckType-Turtle',
                'Material-Linen',
            ],
            'Color-Black' => [
                'Material-Linen',
            ],
            'Color-White' => [
                'Material-Leather',
            ],
            'Color-Red' => [
                'NeckType-Crew',
                'NeckType-Turtle',
            ],
            'NeckType-V' => [
                'Material-Leather',
                'Material-Wool',
            ],
        ];

        $this->mapProhibited($values, $prohibited);

        $manager->flush();
    }

    public static function getGroups() : array
    {
        return ['clothing'];
    }

    private function createParameters(ObjectManager $manager, array $parameterNames)
    {
        $parameters = [];
        foreach($parameterNames as $name) {
            $parameters[$name] = new Parameter(null, $name);
            $manager->persist($parameters[$name]);
        }
        return $parameters;
    }

    private function createValues(ObjectManager $manager, Parameter $parameter, array $valueNames)
    {
        $values = [];
        foreach ($valueNames as $name) {
            $values[$name] = new ParameterValue($parameter, null, $name);
            $manager->persist($values[$name]);
        }
        return $values;
    }

    private function mapProhibited($values, array $prohibited)
    {
        foreach ($prohibited as $keyA => $prohibitions) {
            list($paramA, $valueA) = explode('-', $keyA);
            /** @var ParameterValue $a */
            $a = $values[$paramA][$valueA];

            foreach ($prohibitions as $keyB) {
                list($paramB, $valueB) = explode('-', $keyB);
                /** @var ParameterValue $b */
                $b = $values[$paramB][$valueB];

                $a->addProhibitedValue($b);
            }
        }
    }
}
