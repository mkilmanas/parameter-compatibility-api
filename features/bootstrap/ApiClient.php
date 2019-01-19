<?php
declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ApiClient
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->reset();
    }

    public function reset()
    {
        $this->response = null;
    }

    public function request(string $uri, array $params) : void
    {
        $this->request = Request::create($uri, 'GET', $params);
        $this->request->headers->add(['Content-Type' => 'application/json', 'Accept' => 'application/json']);
        $this->response = $this->kernel->handle($this->request);
    }

    public function getStatusCode() : int
    {
        if (!$this->response) {
            throw new \Exception("Failed to receive API response");
        }

        return $this->response->getStatusCode();
    }

    public function getJsonData() : array
    {
        if (!$this->response) {
            throw new \Exception("Failed to receive API response");
        }

        $data = @json_decode($this->response->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to parse API response as JSON: " . json_last_error_msg());
        }

        return $data;
    }
}