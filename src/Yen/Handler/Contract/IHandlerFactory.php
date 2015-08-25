<?php

namespace Yen\Handler\Contract;

interface IHandlerFactory
{
    public function makeHandler($handler_name);
}
