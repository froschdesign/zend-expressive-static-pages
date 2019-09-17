<?php

declare(strict_types=1);

namespace StaticPages\Test\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use StaticPages\Handler\StaticPagesHandler;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class StaticPagesHandlerTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    /** @var RouterInterface|ObjectProphecy */
    protected $router;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->router    = $this->prophesize(RouterInterface::class);
    }

    public function testReturnsHtmlResponseWhenRequestedRouteIsNamedCorrectly()
    {
        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $renderer->reveal()
        );

        /** @var RouteResult|ObjectProphecy $routerResult */
        $routerResult = $this->prophesize(RouteResult::class);
        $routerResult->getMatchedRouteName()
            ->willReturn('static.terms');

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(RouteResult::class)
            ->willReturn($routerResult);
        $response = $page->handle($request->reveal());

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}