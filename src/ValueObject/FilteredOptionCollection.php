<?php
declare(strict_types=1);


namespace App\ValueObject;


use App\Entity\Parameter;
use Doctrine\Common\Collections\Collection;

class FilteredOptionCollection
{
    /** @var Parameter */
    private $parameter;

    /** @var Collection */
    private $options;

    public function __construct(Parameter $parameter, Collection $options)
    {
        $this->parameter = $parameter;
        $this->options = $options;
    }

    public function getParameter() : Parameter
    {
        return $this->parameter;
    }

    public function getOptions() : Collection
    {
        return $this->options;
    }
}