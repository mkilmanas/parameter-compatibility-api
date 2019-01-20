<?php

namespace spec\App\ValueObject;

use App\Entity\Parameter;
use App\ValueObject\FilteredOptionCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilteredOptionCollectionSpec extends ObjectBehavior
{
    function let(Parameter $parameter, Collection $options)
    {
        $this->beConstructedWith($parameter, $options);
    }

    function it_is_initializable_with_parameter_and_value_collection()
    {
        $this->shouldHaveType(FilteredOptionCollection::class);
    }

    function it_throws_exception_when_trying_to_initialize_without_parameters()
    {
        $this->beConstructedWith();
        $this->shouldThrow()->duringInstantiation();
    }

    function it_returns_the_injected_parameter(Parameter $parameter)
    {
        $this->getParameter()->shouldReturn($parameter);
    }

    function it_returns_the_injected_collection_of_options(Collection $options)
    {
        $this->getOptions()->shouldReturn($options);
    }
}
