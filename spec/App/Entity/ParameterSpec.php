<?php

namespace spec\App\Entity;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParameterSpec extends ObjectBehavior
{
    function it_is_initializable_without_parameters()
    {
        $this->beConstructedWith();
        $this->shouldHaveType(Parameter::class);
    }

    function it_is_initializable_with_parameters(Collection $collection)
    {
        $this->beConstructedWith("FAKE-UUID", "Param1", $collection);
        $this->shouldHaveType(Parameter::class);
    }

    function its_id_is_autogenerated_uuid()
    {
        $this->getId()->shouldMatch('/^[A-Fa-f0-9]{8}(-[A-Fa-f0-9]{4}){3}-[A-Fa-f0-9]{12}$/');
    }

    function its_id_can_be_set_via_constructor()
    {
        $this->beConstructedWith("FAKE-UUID");
        $this->getId()->shouldReturn("FAKE-UUID");
    }

    function its_name_is_empty_by_default()
    {
        $this->getName()->shouldReturn("");
    }

    function its_name_can_be_set_via_constructor()
    {
        $this->beConstructedWith(null, "Dummy");
        $this->getName()->shouldReturn("Dummy");
    }

    function its_values_are_empty_by_default()
    {
        $this->getValues()->shouldImplement(Collection::class);
        $this->getValues()->shouldBeEmpty();
    }

    function its_values_can_be_set_via_constructor(Collection $values)
    {
        $this->beConstructedWith(null, null, $values);
        $this->getValues()->shouldReturn($values);
    }

    function its_values_can_be_added(ParameterValue $value)
    {
        $this->addValue($value);
        $this->getValues()->shouldContain($value);
    }

    function it_only_adds_the_value_once(ParameterValue $value)
    {
        $this->addValue($value);
        $this->addValue($value);
        $this->getValues()->shouldHaveCount(1);
    }

    function its_values_can_be_removed(ParameterValue $value)
    {
        $this->addValue($value);
        $this->removeValue($value);
        $this->getValues()->shouldNotContain($value);
    }

    function it_silently_skips_removal_of_value_that_was_not_added(ParameterValue $value)
    {
        $this->shouldNotThrow()->during('removeValue', [$value]);
    }
}
