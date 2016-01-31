<?php

namespace Yen\Core\Contract;

interface IDependencyContainer
{
    /**
     * @return Yen\Router\Contract\IRouter
     */
    public function getRouter();

    /**
     * @return Yen\Handler\Contract\IHandlerRegistry
     */
    public function getHandlerRegistry();

    /**
     * @return Yen\View\Contract\IViewRegistry
     */
    public function getViewRegistry();
}
