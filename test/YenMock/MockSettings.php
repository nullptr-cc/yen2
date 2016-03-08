<?php

namespace YenMock;

use Yen\Settings\Contract\ISettings;

trait MockSettings
{
    protected function mockSettings(array $get = [], array $lookup = [])
    {
        $settings = $this->prophesize(ISettings::class);

        foreach ($get as $key => $value) {
            $settings->get($key)->willReturn($value);
        };

        foreach ($lookup as $key => list($value, $fallback)) {
            $settings->lookup($key, $fallback)->willReturn($value);
        };

        return $settings->reveal();
    }
}
