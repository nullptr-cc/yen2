<?php

namespace Yen\Handler;

use Yen\Handler\Contract\IHandler;
use Yen\Handler\Contract\IHandlerFactory;
use Yen\Handler\Contract\IHandlerRegistry;
use Yen\Handler\Exception\HandlerNotFound;
use Yen\Handler\Exception\HandlerNotMaked;

class HandlerRegistry implements IHandlerRegistry
{
    private $factory;
    private $handlers;
    private $not_found_handler;

    public function __construct(IHandlerFactory $factory)
    {
        $this->factory = $factory;
        $this->handlers = [];
    }

    /**
     * @param string $name - short or conventional name of handler
     * @return Yen\Handler\Contract\IHandler
     * @throws Yen\Handler\Exception\HandlerNotFound
     */
    public function getHandler($name)
    {
        return $this->getOrMakeHandler($name);
    }

    /**
     * @param string $name - short or conventional name of handler
     * @return bool
     */
    public function hasHandler($name)
    {
        try {
            $handler = $this->getOrMakeHandler($name);
            return true;
        } catch (HandlerNotFound $ex) {
            return false;
        };
    }

    public function setNotFoundHandler(IHandler $not_found_handler)
    {
        $this->not_found_handler = $not_found_handler;

        return $this;
    }

    public function getNotFoundHandler()
    {
        if (!$this->not_found_handler) {
            throw new \LogicException('Not found handler have not been defined');
        };

        return $this->not_found_handler;
    }

    /**
     * @param string $name - short or conventional name of handler
     * @return Yen\Handler\Contract\IHandler
     * @throws Yen\Handler\Exception\HandlerNotFound
     */
    private function getOrMakeHandler($name)
    {
        if (array_key_exists($name, $this->handlers)) {
            return $this->handlers[$name];
        };

        try {
            $handler = $this->factory->makeHandler($name);
            $this->handlers[$name] = $handler;
            return $handler;
        } catch (HandlerNotMaked $ex) {
            throw new HandlerNotFound($name, 0, $ex);
        };
    }
}
