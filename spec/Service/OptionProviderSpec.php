<?php

namespace spec\App\Service;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use App\Service\OptionProvider;
use App\ValueObject\FilteredOptionCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionProviderSpec extends ObjectBehavior
{
    function let(ObjectRepository $parameterRepository)
    {
        $this->beConstructedWith($parameterRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OptionProvider::class);
    }

    function it_provides_all_parameters_and_all_values_when_the_selection_is_empty(
        ObjectRepository $parameterRepository
    ) {
        $parameterA = new Parameter('A', 'A');
        $parameterB = new Parameter('B', 'B');

        $parameterA->addValue($valueX = new ParameterValue($parameterA, 'X', 'X'));
        $parameterB->addValue($valueY = new ParameterValue($parameterA, 'Y', 'Y'));
        $parameterB->addValue($valueZ = new ParameterValue($parameterA, 'Z', 'Z'));

        $parameterRepository->findAll()->willReturn([$parameterA, $parameterB]);

        $filteredOptionsA = new FilteredOptionCollection($parameterA, $parameterA->getValues());
        $filteredOptionsB = new FilteredOptionCollection($parameterB, $parameterB->getValues());

        $selection = $this->provideForSelection(new ArrayCollection());
        $selection->shouldHaveCount(2);
        $selection[0]->shouldBeLike($filteredOptionsA);
        $selection[1]->shouldBeLike($filteredOptionsB);
    }

    function it_removes_values_prohibited_by_selection(
        ObjectRepository $parameterRepository
    ) {
        $parameterA = new Parameter('A', 'A');
        $parameterB = new Parameter('B', 'B');

        $parameterA->addValue($valueX = new ParameterValue($parameterA, 'X', 'X'));
        $parameterB->addValue($valueY = new ParameterValue($parameterA, 'Y', 'Y'));
        $parameterB->addValue($valueZ = new ParameterValue($parameterA, 'Z', 'Z'));

        $valueX->addProhibitedValue($valueY);

        $parameterRepository->findAll()->willReturn([$parameterA, $parameterB]);

        $selection = new ArrayCollection([$valueX]);

        $filteredOptionsA = new FilteredOptionCollection($parameterA, $parameterA->getValues());

        $selection = $this->provideForSelection($selection);
        $selection->shouldHaveCount(2);
        $selection[0]->shouldBeLike($filteredOptionsA);
        $selection[1]->getParameter()->shouldBe($parameterB);
        $selection[1]->getOptions()->shouldHaveCount(1);
        $selection[1]->getOptions()->shouldContain($valueZ);
    }
}
