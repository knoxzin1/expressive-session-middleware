<?php

namespace DaMess\Test\Http;

use Aura\Session\Session;
use DaMess\Http\SessionMiddleware;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class SessionMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Session
     */
    protected $session;

    public function setUp()
    {
        $this->session = $this->prophesize(Session::class);
    }

    public function testSessionStarts()
    {
        $next = new class extends \PHPUnit_Framework_TestCase implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->assertInstanceOf(Session::class, $request->getAttribute(SessionMiddleware::KEY));
                return new Response\EmptyResponse();
            }
        };

        $this->session->isStarted()->shouldBeCalled();
        $this->session->start()->shouldBeCalled();
        $middleware = new SessionMiddleware($this->session->reveal());
        $middleware->process(new ServerRequest(), $next);
    }
}
