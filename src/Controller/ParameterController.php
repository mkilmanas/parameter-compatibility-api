<?php
declare(strict_types=1);


namespace App\Controller;


use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterController extends AbstractFOSRestController
{
    function cgetAction(Request $request, EntityManagerInterface $entityManager)
    {
        $params = $entityManager->getRepository(Parameter::class)->findAll();

        /** @var ParameterValue[] $selection */
        $selection = [];
        foreach ($request->query as $paramName => $paramValue) {
            /** @var Parameter $param */
            $param = $entityManager->getRepository(Parameter::class)->findOneBy(['name' => $paramName]);
            if (!$param) {
                return new JsonResponse(['error' => "Could not find parameter '{$paramName}'"], Response::HTTP_NOT_FOUND);
            }

            /** @var ParameterValue $value */
            $value = $entityManager->getRepository(ParameterValue::class)->findOneBy(['parameter' => $param, 'value' => $paramValue]);
            if (!$value) {
                return new JsonResponse(['error' => "Could not find value '{$paramValue}' for parameter '{$paramName}''"], Response::HTTP_NOT_FOUND);
            }

            foreach ($selection as $anotherValue) {
                if ($value->getProhibitedValues()->contains($anotherValue)) {
                    return new JsonResponse(['error' => "Your current selection already has incompatible values: '{$value->getValue()}' cannot go with '{$anotherValue->getValue()}'"], Response::HTTP_BAD_REQUEST);
                }
            }

            $selection[] = $value;
        }

        $data = [];
        foreach ($params as $param) {
            $data[$param->getName()] = $param->getValues()
                ->filter(function (ParameterValue $v) use ($selection) {
                    foreach ($selection as $selectedValue) {
                        if ($selectedValue->getProhibitedValues()->contains($v)) {
                            return false;
                        }
                    }
                    return true;
                })
                ->map(function (ParameterValue $v) { return $v->getValue(); })
                ->toArray();
        }

        return $this->handleView(
            $this->view($data)
        );
    }
}