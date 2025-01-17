<?php

declare(strict_types=1);

namespace StaticPages\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Exception\MissingDependencyException;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class StaticPagesFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $router = $this->getRouter($container);
        $template = $this->getTemplateRenderer($container);

        return new StaticPagesHandler($router, $template);
    }

    /**
     * @param ContainerInterface $container
     * @return RouterInterface
     * @throws MissingDependencyException
     */
    public function getRouter(ContainerInterface $container) : RouterInterface
    {
        if ($container->has(RouterInterface::class)) {
           return $container->get(RouterInterface::class);
        }

        throw new MissingDependencyException("RouterInterface object not found in the container");
    }

    /**
     * @param ContainerInterface $container
     * @return TemplateRendererInterface
     * @throws MissingDependencyException
     */
    public function getTemplateRenderer(ContainerInterface $container) : TemplateRendererInterface
    {
        if ($container->has(TemplateRendererInterface::class)) {
            return $container->get(TemplateRendererInterface::class);
        }

        throw new MissingDependencyException("TemplateRendererInterface object not found in the container");
    }
}
