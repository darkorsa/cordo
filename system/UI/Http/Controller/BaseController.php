<?php declare(strict_types=1);

namespace System\UI\Http\Controller;

use Error;
use Exception;
use DI\NotFoundException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

abstract class BaseController
{
    protected $container;

    protected $logger;

    protected $commandBus;

    protected $statusCode = 200;
    
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->container = $container;
        $this->logger = $logger;
        $this->commandBus = $container->get('command_bus');
    }

    public function run()
    {
        $args = func_get_args();
        
        $call       = $args[0];
        $id         = isset($args[1]) ? $args[1] : null;
        $attributes = array_slice($args, 2);
               
        try {
            return $this->{$call . 'Action'}($id, $attributes);
        } catch (NotFoundException $e) {
            return $this->respondNotFound();
        } catch (Error $e) {
            $this->logger->error($e);
            return $this->respondInternalError();
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e);
            return $this->respondBadRequestError();
        } catch (Exception $e) {
            $this->logger->error($e);
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
            'status_code' => $this->statusCode
        ];

        return new Response($this->statusCode, $headers, (string) json_encode($body));
    }
}
