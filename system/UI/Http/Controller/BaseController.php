<?php

declare(strict_types=1);

namespace System\UI\Http\Controller;

use Error;
use Exception;
use DI\NotFoundException;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use System\Application\Error\ErrorReporterInterface;
use System\UI\Transformer\TransformerManagerInterface;
use System\Application\Exception\ResourceNotFoundException;

abstract class BaseController
{
    protected $container;

    protected $errorReporter;

    protected $transformerManager;

    protected $commandBus;

    protected $statusCode = 200;

    public function __construct(
        ContainerInterface $container,
        ErrorReporterInterface $errorReporter,
        TransformerManagerInterface $transformer
    ) {
        $this->container            = $container;
        $this->errorReporter        = $errorReporter;
        $this->transformerManager   = $transformer;
        $this->commandBus           = $container->get('command_bus');

        $this->registerTransformers();
    }

    public function run(RequestInterface $request, string $call, array $vars)
    {
        try {
            return $this->{$call . 'Action'}($request, $vars);
        } catch (NotFoundException | ResourceNotFoundException $exception) {
            return $this->respondNotFound();
        } catch (InvalidArgumentException $exception) {
            $this->errorReporter->report($exception);
            return $this->respondBadRequestError();
        } catch (Error | Exception $exception) {
            $this->errorReporter->report($exception);
            return $this->respondInternalError();
        }
    }

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    protected function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respond($message);
    }

    protected function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(500)->respond($message);
    }

    protected function respondBadRequestError($message = 'Bad request or invalid parameters')
    {
        return $this->setStatusCode(400)->respond($message);
    }

    protected function respondWithSuccess($message = 'OK')
    {
        return $this->setStatusCode(200)->respond($message);
    }

    protected function respondWithData($response, $headers = [])
    {
        return $this->setStatusCode(200)->respond($response, $headers);
    }

    protected function respond($response, array $headers = [])
    {
        $body = [
            'response' => $response,
        ];

        return new Response($this->statusCode, $headers, (string) json_encode($body));
    }

    protected function registerTransformers(): void
    {
        // implement where needed
    }
}
