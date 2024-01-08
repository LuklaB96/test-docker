<?php

use PHPUnit\Framework\TestCase;
use App\Lib\Routing\Router;

final class RouterGetRequestTest extends TestCase
{
    private $router;
    protected function setUp(): void
    {
        $this->router = Router::getInstance('test router');
    }
    protected function tearDown(): void
    {
        $this->router->clear();
    }
    public function testValidGetRoute(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $this->router->get('/test', function () {
            //valid route
        });

        $valid = $this->router->dispatch();

        $this->assertTrue($valid);
    }
    public function testInvalidGetRouter(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test';

        $this->router->get('/test', function () {
            //invalid route
        });

        $valid = $this->router->dispatch();

        $this->assertFalse($valid);
    }
}