<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\ParameterValue;
use App\ValueObject\FilteredOptionCollection;

class FilteredOptionSerializer
{
    public function serialize(array $options)
    {
        return array_combine(
            array_map(
                function (FilteredOptionCollection $foc)
                {
                    return $foc->getParameter()->getName();
                },
                $options
            ),
            array_map(
                function (FilteredOptionCollection $foc)
                {
                    return array_values($foc->getOptions()->map(function (ParameterValue $pv) { return $pv->getValue(); })->toArray());
                },
                $options
            )
        );
    }
}