<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\ParameterValue;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidSelectionException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;

class SelectionParser
{
    /**
     * @var ObjectRepository
     */
    private $parameterRepository;
    /**
     * @var ObjectRepository
     */
    private $valueRepository;

    public function __construct(ObjectRepository $parameterRepository, ObjectRepository $valueRepository)
    {
        $this->parameterRepository = $parameterRepository;
        $this->valueRepository = $valueRepository;
    }

    public function parseRequest(Request $request)
    {
        $selection = new ArrayCollection();

        foreach ($request->query as $parameterName => $parameterValue) {
            $parameter = $this->parameterRepository->findOneBy(['name' => $parameterName]);
            if (empty($parameter)) {
                throw new EntityNotFoundException("Could not find Parameter named '{$parameterName}'");
            }

            $value = $this->valueRepository->findOneBy(['parameter' => $parameter, 'value' => $parameterValue]);
            if (empty($value)) {
                throw new EntityNotFoundException("Could not find Value '{$parameterValue}' for Parameter '{$parameterName}'");
            }

            $selection->add($value);
        }

        $this->validate($selection);

        return $selection;
    }

    private function validate(Collection $selection)
    {
        /** @var ParameterValue[] $selection */
        foreach ($selection as $valueA) {
            foreach ($selection as $valueB) {
                if ($valueA === $valueB) {
                    continue;
                }

                if ($valueA->getProhibitedValues()->contains($valueB)) {
                    throw new InvalidSelectionException("Selected values '{$valueA->getValue()}' and '{$valueB->getValue()}' are incompatible");
                }
            }
        }
    }
}