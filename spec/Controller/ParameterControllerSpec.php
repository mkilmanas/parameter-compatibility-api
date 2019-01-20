<?php

namespace spec\App\Controller;

use App\Controller\ParameterController;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidSelectionException;
use App\Service\FilteredOptionSerializer;
use App\Service\OptionProvider;
use App\Service\SelectionParser;
use App\ValueObject\FilteredOptionCollection;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterController::class);
    }

    function it_returns_a_response_with_prameter_and_value_json_generated_by_a_combination_of_services(
        Request $request,
        SelectionParser $selectionParser,
        OptionProvider $optionProvider,
        FilteredOptionCollection $filteredOptionCollection,
        FilteredOptionSerializer $filteredOptionSerializer,
        ViewHandlerInterface $viewHandler,
        Collection $selection,
        Response $response
    ) {
        $selectionParser->parseRequest($request)->willReturn($selection);
        $optionProvider->provideForSelection($selection)->willReturn([$filteredOptionCollection]);

        $filteredOptionSerializer->serialize([$filteredOptionCollection])->willReturn($data = ['A' => ['X', 'Y', 'Z']]);

        $this->setViewHandler($viewHandler);
        $viewHandler->handle(Argument::that(function (View $view) use ($data) {
            return $view->getData() == $data;
        }))->willReturn($response);

        $this->cgetAction($request, $selectionParser, $optionProvider, $filteredOptionSerializer)->shouldReturn($response);
    }

    function it_returns_a_404_json_response_with_an_error_when_selection_cannot_be_loaded_as_entities(
        Request $request,
        SelectionParser $selectionParser,
        OptionProvider $optionProvider,
        FilteredOptionSerializer $filteredOptionSerializer
    ) {
        $selectionParser->parseRequest($request)->willThrow(new EntityNotFoundException('Error X'));

        $response = $this->cgetAction($request, $selectionParser, $optionProvider, $filteredOptionSerializer);
        $response->shouldHaveType(JsonResponse::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_NOT_FOUND);
        $response->getContent()->shouldReturn('{"error":"Error X"}');
    }

    function it_returns_a_400_json_response_with_an_error_when_selection_contains_invalid_combination(
        Request $request,
        SelectionParser $selectionParser,
        OptionProvider $optionProvider,
        FilteredOptionSerializer $filteredOptionSerializer
    ) {
        $selectionParser->parseRequest($request)->willThrow(new InvalidSelectionException('Error Z'));

        $response = $this->cgetAction($request, $selectionParser, $optionProvider, $filteredOptionSerializer);
        $response->shouldHaveType(JsonResponse::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_BAD_REQUEST);
        $response->getContent()->shouldReturn('{"error":"Error Z"}');
    }
}
