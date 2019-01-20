<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;

class OptionProvider
{
    /**
     * @var ObjectRepository
     */
    private $parameterRepository;

    public function __construct(ObjectRepository $parameterRepository)
    {
        $this->parameterRepository = $parameterRepository;
    }

    /**
     * @param Collection $selection
     * @return array
     */
    public function provideForSelection(Collection $selection)
    {
        /** @var ParameterValue[] $selection */
        /** @var Parameter[] $params */
        $params = $this->parameterRepository->findAll();

        $result = [];

        foreach ($params as $param) {
            $result[$param->getName()] = array_values(
                $param->getValues()
                    ->filter(
                        function (ParameterValue $v) use ($selection)
                        {
                            foreach ($selection as $selectedValue) {
                                if ($selectedValue->getProhibitedValues()->contains($v)) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    )
                    ->map(function (ParameterValue $v) { return $v->getValue(); })
                    ->toArray()
            );
        }

        return $result;
    }
}