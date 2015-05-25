<?php

namespace KB\Controller;

use KB\Http\Request;
use KB\Router\RouteMatcher;
use Interop\Container\ContainerInterface;

/**
 * Class ControllerResolver
 */
class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var RouteMatcher
     */
    private $matcher;

    /**
     * @var array
     */
    private $controllers = [];

    /**
     * @param RouteMatcher $matcher
     * @param ContainerInterface $container
     */
    public function __construct(RouteMatcher $matcher, ContainerInterface $container)
    {
        $this->matcher = $matcher;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return array|callable
     */
    public function resolve(Request $request)
    {
        $action = $this->matcher->match($request);
        $splitAction = explode('::', $action);
        $className = $splitAction[0];

        /*
        foreach ($this->controllers as $controller) {
            if ($className == '\\'.get_class($controller)) {
                $action = $splitAction[1];

                return [$controller, $action];
            }
        }*/

        if ($this->container->has($className)) {
            return [$this->container->get($className), $action];
        }
        
        throw new ControllerNotFound($className);
    }

    /**
     * @param $controllerInstance
     * @throws \Exception
     */
    public function addController($controller)
    {
        if (is_object($controller)) {
            $this->container->set('\\' . get_class($controller), $controller);
            //throw new \Exception('$controllerInstance must be an object');
        } else {
            $this->container->set('\\' . $controller, \DI\Object($controller)->lazy());
        }
        //$this->controllers[] = $controllerInstance;
    }
}
