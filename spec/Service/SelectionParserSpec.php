<?php

namespace spec\App\Service;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidSelectionException;
use App\Service\SelectionParser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class SelectionParserSpec extends ObjectBehavior
{
    public function let(ObjectRepository $parameterRepository, ObjectRepository $valueRepository)
    {
        $this->beConstructedWith($parameterRepository, $valueRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SelectionParser::class);
    }

    function it_returns_an_empty_collection_when_no_selection_is_provided(Request $request)
    {
        $request->query = new ParameterBag();

        $result = $this->parseRequest($request);

        $result->shouldHaveType(Collection::class);
        $result->shouldBeEmpty();
    }

    function it_loads_selection_value_as_entity(
        ObjectRepository $parameterRepository,
        ObjectRepository $valueRepository,
        Request $request,
        Parameter $parameter,
        ParameterValue $value
    ) {
        $request->query = new ParameterBag(['Foo' => 'Bar']);

        $parameterRepository->findOneBy(['name' => 'Foo'])->willReturn($parameter);
        $valueRepository->findOneBy(['parameter' => $parameter, 'value' => 'Bar'])->willReturn($value);

        $selection = $this->parseRequest($request);

        $selection->count()->shouldReturn(1);
        $selection->first()->shouldReturn($value);
    }

    function it_loads_multiple_selection_values_as_entities(
        ObjectRepository $parameterRepository,
        ObjectRepository $valueRepository,
        Request $request,
        Parameter $parameter1,
        Parameter $parameter2,
        ParameterValue $value1,
        ParameterValue $value2,
        Collection $prohibitedValues
    ) {
        $request->query = new ParameterBag(['Foo' => 'Bar', 'Baz' => 'Tee']);

        $parameterRepository->findOneBy(['name' => 'Foo'])->willReturn($parameter1);
        $parameterRepository->findOneBy(['name' => 'Baz'])->willReturn($parameter2);
        $valueRepository->findOneBy(['parameter' => $parameter1, 'value' => 'Bar'])->willReturn($value1);
        $valueRepository->findOneBy(['parameter' => $parameter2, 'value' => 'Tee'])->willReturn($value2);

        $value1->getProhibitedValues()->willReturn($prohibitedValues);
        $value2->getProhibitedValues()->willReturn($prohibitedValues);

        $prohibitedValues->contains(Argument::any())->willReturn(false);

        $selection = $this->parseRequest($request);

        $selection->count()->shouldReturn(2);
        $selection->first()->shouldReturn($value1);
        $selection->last()->shouldReturn($value2);
    }

    function it_throws_exception_when_parameter_is_not_found(
        ObjectRepository $parameterRepository,
        Request $request
    ) {
        $request->query = new ParameterBag(['FooX' => 'BarX']);

        $parameterRepository->findOneBy(['name' => 'FooX'])->willReturn(null);

        $this->shouldThrow(EntityNotFoundException::class)->during('parseRequest', [$request]);
    }

    function it_throws_exception_when_parameter_value_is_not_found(
        ObjectRepository $parameterRepository,
        ObjectRepository $valueRepository,
        Request $request,
        Parameter $parameter
    ) {
        $request->query = new ParameterBag(['Foo' => 'BarX']);

        $parameterRepository->findOneBy(['name' => 'Foo'])->willReturn($parameter);
        $valueRepository->findOneBy(['parameter' => $parameter, 'value' => 'BarX'])->willReturn(null);

        $this->shouldThrow(EntityNotFoundException::class)->during('parseRequest', [$request]);
    }

    function it_throws_exception_if_selection_already_contains_incompatible_values(
        ObjectRepository $parameterRepository,
        ObjectRepository $valueRepository,
        Request $request,
        Parameter $parameter1,
        Parameter $parameter2,
        ParameterValue $value1,
        ParameterValue $value2,
        Collection $prohibitedValues
    ) {
        $request->query = new ParameterBag(['Foo' => 'Bar', 'Baz' => 'Tee']);

        $parameterRepository->findOneBy(['name' => 'Foo'])->willReturn($parameter1);
        $parameterRepository->findOneBy(['name' => 'Baz'])->willReturn($parameter2);
        $valueRepository->findOneBy(['parameter' => $parameter1, 'value' => 'Bar'])->willReturn($value1);
        $valueRepository->findOneBy(['parameter' => $parameter2, 'value' => 'Tee'])->willReturn($value2);

        $value1->getProhibitedValues()->willReturn($prohibitedValues);

        $prohibitedValues->contains($value2)->willReturn(true);

        $value1->getValue()->willReturn('Bar');
        $value2->getValue()->willReturn('Tee');

        $this->shouldThrow(InvalidSelectionException::class)->during('parseRequest', [$request]);
    }
}
