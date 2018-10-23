<?php
/**
 * Created by PhpStorm.
 * User: Marvin Petker
 * Date: 23.10.2018
 * Time: 10:14
 */

namespace Crtl\AuthorizationMiddleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract middleware implementation
 * Extend to implement custom authorization logic
 * @package Borntocreate\AuthorizationMiddleware
 */
abstract class AbstractAuthorization
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * AbstractAuthorization constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return array
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->request = $request;
        $this->response = $response;

        if ($this->isAuthorized()) {
            return $next($request, $response);
        }

        return $this->getErrorResponse();
    }

    /**
     * @return array
     */
    protected function getErrorResponse() {

        $data = [
            "status" => 401,
            "error" => [
                "code" => 401,
                "code_detail" => null,
                "message" => "Unauthorized",
                "title" => "Unauthorized"
            ]
        ];

        $body = $this->response->getBody();
        $body->write(json_encode($data));

        return $this->response
            ->withStatus(401, "Unauthorized")
            ->withBody($body);
    }

    abstract protected function isAuthorized(): bool;

}