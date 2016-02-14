<?php

namespace Yen\View\Contract;

interface IViewRegistry
{
    /**
     * @return Yen\View\Contract\IView
     */
    public function getView($name);
}
