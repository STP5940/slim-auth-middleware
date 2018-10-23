<?php
/**
 * Created by PhpStorm.
 * User: Marvin Petker
 * Date: 23.10.2018
 * Time: 10:14
 */

namespace Crtl\Authorization;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract middleware implementation
 * Extend to implement custom authorization logic
 * @package Borntocreate\Authorization
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


    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->request = $request;
        $this->response = $response;

        if ($this->isAuthorized()) {
            return $next($request, $response);
        }

        $body = $response->getBody();
        $body->write(json_encode($this->getError()));

        return $response->withStatus(401, "Unauthorized")
            ->withBody($body);

    }

    protected function getError() {
        return [
            "status" => 401,
            "error" => [
                "code" => 401,
                "code_detail" => null,
                "message" => "Unauthorized",
                "title" => "Unauthorized"
            ]
        ];
    }

    abstract protected function isAuthorized();

}