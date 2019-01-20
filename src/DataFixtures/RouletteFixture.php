<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RouletteFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $params = $this->createParameters($manager, ['Number', 'Color', 'Parity', 'Parity3', 'Half', 'Third']);

        $values['Number'] = $this->createValues($manager, $params['Number'], array_map('strval', range(0, 36)));
        $values['Color'] = $this->createValues($manager, $params['Color'], ['Black', 'Red']);
        $values['Parity'] = $this->createValues($manager, $params['Parity'], ['Odd', 'Even']);
        $values['Parity3'] = $this->createValues($manager, $params['Parity3'], ['1', '2', '3']);
        $values['Half'] = $this->createValues($manager, $params['Half'], ['1st', '2nd', ]);
        $values['Third'] = $this->createValues($manager, $params['Third'], ['1st', '2nd', '3rd']);

        $prohibited = [
            'Number-0' => [
                'Color-Red',
                'Color-Black',
                'Parity-Odd',
                'Parity-Even',
                'Half-1st',
                'Half-2nd',
                'Third-1st',
                'Third-2nd',
                'Third-3rd',
            ],
            'Color-Red' => [
                'Number-2',
                'Number-4',
                'Number-6',
                'Number-8',
                'Number-10',
                'Number-11',
                'Number-13',
                'Number-15',
                'Number-17',
                'Number-20',
                'Number-22',
                'Number-24',
                'Number-26',
                'Number-28',
                'Number-29',
                'Number-31',
                'Number-33',
                'Number-35',
            ],
            'Color-Black' => [
                'Number-1',
                'Number-3',
                'Number-5',
                'Number-7',
                'Number-9',
                'Number-12',
                'Number-14',
                'Number-16',
                'Number-18',
                'Number-29',
                'Number-21',
                'Number-23',
                'Number-25',
                'Number-27',
                'Number-30',
                'Number-32',
                'Number-34',
                'Number-36',
            ],
            'Parity-Odd' => [
                'Number-2',
                'Number-4',
                'Number-6',
                'Number-8',
                'Number-10',
                'Number-12',
                'Number-14',
                'Number-16',
                'Number-18',
                'Number-20',
                'Number-22',
                'Number-24',
                'Number-26',
                'Number-28',
                'Number-30',
                'Number-32',
                'Number-34',
                'Number-36',
            ],
            'Parity-Even' => [
                'Number-1',
                'Number-3',
                'Number-5',
                'Number-7',
                'Number-9',
                'Number-11',
                'Number-13',
                'Number-15',
                'Number-17',
                'Number-19',
                'Number-21',
                'Number-23',
                'Number-25',
                'Number-27',
                'Number-29',
                'Number-31',
                'Number-33',
                'Number-35',
            ],
            'Parity3-1' => [
                'Number-2',
                'Number-3',
                'Number-5',
                'Number-6',
                'Number-8',
                'Number-9',
                'Number-11',
                'Number-12',
                'Number-14',
                'Number-15',
                'Number-17',
                'Number-18',
                'Number-20',
                'Number-21',
                'Number-23',
                'Number-24',
                'Number-26',
                'Number-27',
                'Number-29',
                'Number-30',
                'Number-31',
                'Number-33',
                'Number-35',
                'Number-36',
            ],
            'Parity3-2' => [
                'Number-1',
                'Number-3',
                'Number-4',
                'Number-6',
                'Number-7',
                'Number-9',
                'Number-10',
                'Number-12',
                'Number-13',
                'Number-15',
                'Number-16',
                'Number-18',
                'Number-19',
                'Number-21',
                'Number-22',
                'Number-24',
                'Number-25',
                'Number-27',
                'Number-28',
                'Number-30',
                'Number-31',
                'Number-33',
                'Number-34',
                'Number-36',
            ],
            'Parity3-3' => [
                'Number-1',
                'Number-2',
                'Number-4',
                'Number-5',
                'Number-7',
                'Number-8',
                'Number-10',
                'Number-11',
                'Number-13',
                'Number-14',
                'Number-16',
                'Number-17',
                'Number-19',
                'Number-20',
                'Number-22',
                'Number-23',
                'Number-25',
                'Number-26',
                'Number-28',
                'Number-29',
                'Number-30',
                'Number-32',
                'Number-34',
                'Number-35',
            ],
            'Half-1st' => [
                'Number-19',
                'Number-20',
                'Number-21',
                'Number-22',
                'Number-23',
                'Number-24',
                'Number-25',
                'Number-26',
                'Number-27',
                'Number-28',
                'Number-29',
                'Number-30',
                'Number-31',
                'Number-32',
                'Number-33',
                'Number-34',
                'Number-35',
                'Number-36',
            ],
            'Half-2nd' => [
                'Number-1',
                'Number-2',
                'Number-3',
                'Number-4',
                'Number-5',
                'Number-6',
                'Number-7',
                'Number-8',
                'Number-9',
                'Number-10',
                'Number-11',
                'Number-12',
                'Number-13',
                'Number-14',
                'Number-15',
                'Number-16',
                'Number-17',
                'Number-18',
            ],
            'Third-1st' => [
                'Half-2nd',
                'Number-13',
                'Number-14',
                'Number-15',
                'Number-16',
                'Number-17',
                'Number-18',
                'Number-19',
                'Number-20',
                'Number-21',
                'Number-22',
                'Number-23',
                'Number-24',
                'Number-25',
                'Number-26',
                'Number-27',
                'Number-28',
                'Number-29',
                'Number-30',
                'Number-31',
                'Number-32',
                'Number-33',
                'Number-34',
                'Number-35',
                'Number-36',
            ],
            'Third-2nd' => [
                'Number-1',
                'Number-2',
                'Number-3',
                'Number-4',
                'Number-5',
                'Number-6',
                'Number-7',
                'Number-8',
                'Number-9',
                'Number-10',
                'Number-11',
                'Number-12',
                'Number-25',
                'Number-26',
                'Number-27',
                'Number-28',
                'Number-29',
                'Number-30',
                'Number-31',
                'Number-32',
                'Number-33',
                'Number-34',
                'Number-35',
                'Number-36',
            ],
            'Third-3rd' => [
                'Half-1st',
                'Number-1',
                'Number-2',
                'Number-3',
                'Number-4',
                'Number-5',
                'Number-6',
                'Number-7',
                'Number-8',
                'Number-9',
                'Number-10',
                'Number-11',
                'Number-12',
                'Number-13',
                'Number-14',
                'Number-15',
                'Number-16',
                'Number-17',
                'Number-18',
                'Number-19',
                'Number-20',
                'Number-21',
                'Number-22',
                'Number-23',
                'Number-24',
            ],
        ];

        $this->mapProhibited($values, $prohibited);

        $manager->flush();
    }

    public static function getGroups() : array
    {
        return ['roulette'];
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
