<?php

namespace Yen\Settings;

class SettingsArray implements Contract\ISettings
{
    protected $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function get($key)
    {
        $parts = explode('.', $key);

        $ref = &$this->settings;
        while (count($parts)) {
            $sk = array_shift($parts);
            if (!array_key_exists($sk, $ref)) {
                throw new \OutOfBoundsException('Invalid settings key ' . $key);
            };
            $ref = &$ref[$sk];
        };

        return is_array($ref) ? new self($ref) : $ref;
    }

    public function lookup($key, $fallback = null)
    {
        try {
            return $this->get($key);
        } catch (\OutOfBoundsException $ex) {
            return $fallback;
        };
    }
}
