<?php

namespace Yen\Settings\Contract;

interface ISettings
{
    public function get($key);
    public function lookup($key, $fallback = null);
}
