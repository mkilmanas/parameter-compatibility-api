<?php
declare(strict_types=1);


namespace App\Controller;


use App\Entity\ParameterValue;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidSelectionException;
use App\Service\FilteredOptionSerializer;
use App\Service\OptionProvider;
use App\Service\SelectionParser;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterController extends AbstractFOSRestController
{
    function cgetAction(Request $request, SelectionParser $selectionParser, OptionProvider $optionProvider, FilteredOptionSerializer $serializer)
    {
        /** @var ParameterValue[] $selection */
        try {
            $selection = $selectionParser->parseRequest($request);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (InvalidSelectionException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView(
            $this->view(
                $serializer->serialize(
                    $optionProvider->provideForSelection($selection)
                )
            )
        );
    }
}