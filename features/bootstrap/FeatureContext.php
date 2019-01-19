<?php

use App\Entity\Parameter;
use App\Entity\ParameterValue;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use DbCleanupTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ApiClient
     */
    private $api;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(KernelInterface $kernel, EntityManager $entityManager)
    {
        $this->api = new ApiClient($kernel);
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @BeforeScenario
     */
    public function resetApi()
    {
        $this->api->reset();
    }

    /**
     * @Given there is parameter :parameterName with possible values :value1, :value2 and :value3
     * @Given there is parameter :parameterName with possible values :value1, :value2, :value3 and :value4
     */
    public function thereIsParameterWithPossibleValuesAnd($parameterName, $value1, $value2, $value3, $value4 = null)
    {
        $parameter = $this->entityManager->getRepository(Parameter::class)->findOneBy(['name' => $parameterName]);
        if (!$parameter) {
            $parameter = new Parameter(null, $parameterName);
            $this->entityManager->persist($parameter);
        }

        foreach ([$value1, $value2, $value3, $value4] as $value) {
            if ($value === null) {
                continue;
            }

            $parameterValue = $this->entityManager->getRepository(ParameterValue::class)->findOneBy(['parameter' => $parameter, 'value' => $value]);
            if (!$parameterValue) {
                $parameterValue = new ParameterValue($parameter, null, $value);
                $parameter->addValue($parameterValue);
                $this->entityManager->persist($parameterValue);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @Given there is a restriction that :paramName1 :paramValue1 cannot go together with :paramName2 :paramValue2
     */
    public function thereIsARestrictionThatCannotGoTogetherWith($paramName1, $paramValue1, $paramName2, $paramValue2)
    {
        $paramRepository = $this->entityManager->getRepository(Parameter::class);
        $valueRepository = $this->entityManager->getRepository(ParameterValue::class);

        $param1 = $paramRepository->findOneBy(['name' => $paramName1]);
        if (!$param1) {
            throw new \Exception("Could not find required parameter named '{$paramName1}'");
        }

        $param2 = $paramRepository->findOneBy(['name' => $paramName2]);
        if (!$param2) {
            throw new \Exception("Could not find required parameter named '{$paramName2}'");
        }

        $value1 = $valueRepository->findOneBy(['parameter' => $param1, 'value' => $paramValue1]);
        if (!$value1) {
            throw new \Exception("Could not find value '{$paramValue1}' for parameter '{$param1->getName()}'");
        }

        $value2 = $valueRepository->findOneBy(['parameter' => $param2, 'value' => $paramValue2]);
        if (!$value2) {
            throw new \Exception("Could not find value '{$paramValue2}' for parameter '{$param2->getName()}'");
        }

        $value1->addProhibitedValue($value2);
        $this->entityManager->flush();
    }

    /**
     * @When I query the API for parameter options without current selection
     */
    public function iQueryTheApiForParameterOptionsWithoutCurrentSelection()
    {
        $this->api->request('/', []);
    }

    /**
     * @Then I should receive parameter :paramName with choices :value1
     * @Then I should receive parameter :paramName with choices :value1 and :value2
     * @Then I should receive parameter :paramName with choices :value1, :value2 and :value3
     * @Then I should receive parameter :paramName with choices :value1, :value2, :value3 and :value4
     */
    public function iShouldReceiveParameterWithChoicesAnd($paramName, $value1 = null, $value2 = null, $value3 = null, $value4 = null)
    {
        $data = $this->api->getJsonData();

        if (!isset($data[$paramName])) {
            throw new \Exception("Parameter '{$paramName}' was not found in the response'");
        }
        $allowedValues = $data[$paramName];
        $allowedAsString = !empty($allowedValues) ? implode(', ', array_map(function($v) { return "'{$v}'"; }, $allowedValues)) : 'empty list';

        foreach ([$value1, $value2, $value3, $value4] as $value) {
            if ($value === null) {
                continue;
            }

            if (($index = array_search($value, $allowedValues)) === false) {
                throw new \Exception("Expected parameter '{$paramName}' for have choice '{$value}' but it only had $allowedAsString");
            }
            unset($allowedValues[$index]);
        }

        if (!empty($allowedValues)) {
            $remainingAsString = implode(', ', array_map(function($v) { return "'{$v}'"; }, $allowedValues));
            throw new \Exception("Did not expect parameter '{$paramName}' to have any of the values [{$remainingAsString}] but it did");
        }
    }

    /**
     * @When I query the API for parameter options with current selection :paramName being :paramValue
     */
    public function iQueryTheApiForParameterOptionsWithCurrentSelectionBeing($paramName, $paramValue)
    {
        $this->api->request('/', [$paramName => $paramValue]);
    }

    /**
     * @When I query the API for parameter options with current selection :paramName1 being :paramValue1 and :paramName2 being :paramValue2
     */
    public function iQueryTheApiForParameterOptionsWithCurrentSelectionBeingAndBeing($paramName1, $paramValue1, $paramName2, $paramValue2)
    {
        $this->api->request('/', [$paramName1 => $paramValue1, $paramName2 => $paramValue2]);
    }

    /**
     * @Then I should receive receive a status code :code
     */
    public function iShouldReceiveReceiveAStatusCode($code)
    {
        if ($this->api->getStatusCode() !== (int)$code) {
            throw new \Exception("Expected status code {$code} but received {$this->api->getStatusCode()} instead");
        }
    }

    /**
     * @Then I should receive an error message
     */
    public function iShouldReceiveAnErrorMessage()
    {
        $data = $this->api->getJsonData();
        if (!isset($data['error'])) {
            throw new \Exception("Response does not contain error message");
        }
    }


}
