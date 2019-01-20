<?php

namespace spec\App\Service;

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use App\Service\FilteredOptionSerializer;
use App\ValueObject\FilteredOptionCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilteredOptionSerializerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FilteredOptionSerializer::class);
    }

    function it_serializes_empty_list_to_empty_array()
    {
        $this->serialize([])->shouldReturn([]);
    }

    function it_serializes_one_filtered_option_to_a_list_of_one_item(
        FilteredOptionCollection $filteredOptions,
        Parameter $parameter,
        Collection $options,
        Collection $serializedOptions
    ) {
        $filteredOptions->getParameter()->willReturn($parameter);
        $parameter->getName()->willReturn('ParamA');

        $filteredOptions->getOptions()->willReturn($options);

        $isBasicSerializer = function (\Closure $func) {
            $param = new Parameter();
            $value = new ParameterValue($param, null, 'Value1');
            return  $func($value) === 'Value1';
        };

        $options->map(Argument::that($isBasicSerializer))->willReturn($serializedOptions);
        $serializedOptions->toArray()->willReturn(['ValueX']);

        $this->serialize([$filteredOptions])->shouldReturn(['ParamA' => ['ValueX']]);
    }

    function it_serializes_multiple_filtered_option_to_groups_of_parameters_and_their_values(
        FilteredOptionCollection $filteredOptionsA,
        FilteredOptionCollection $filteredOptionsB,
        Parameter $parameterA,
        Parameter $parameterB,
        Collection $optionsA,
        Collection $optionsB,
        Collection $serializedOptionsA,
        Collection $serializedOptionsB
    ) {
        $filteredOptionsA->getParameter()->willReturn($parameterA);
        $filteredOptionsB->getParameter()->willReturn($parameterB);

        $parameterA->getName()->willReturn('ParamA');
        $parameterB->getName()->willReturn('ParamB');

        $filteredOptionsA->getOptions()->willReturn($optionsA);
        $filteredOptionsB->getOptions()->willReturn($optionsB);

        $isBasicSerializer = function (\Closure $func) {
            $param = new Parameter();
            $value = new ParameterValue($param, null, 'Value1');
            return  $func($value) === 'Value1';
        };

        $optionsA->map(Argument::that($isBasicSerializer))->willReturn($serializedOptionsA);
        $optionsB->map(Argument::that($isBasicSerializer))->willReturn($serializedOptionsB);

        $serializedOptionsA->toArray()->willReturn(['ValueX', 'ValueY']);
        $serializedOptionsB->toArray()->willReturn(['AX', 'BX', 'CX']);

        $expectedResult = [
            'ParamA' => ['ValueX', 'ValueY'],
            'ParamB' => ['AX', 'BX', 'CX']
        ];
        $this->serialize([$filteredOptionsA, $filteredOptionsB])->shouldReturn($expectedResult);
    }
}
