<?php

namespace Yen\Router;

final class MatchResult
{
    private $matched;
    private $point;

    private function __construct($matched, RoutePoint $point = null)
    {
        $this->matched = $matched;
        $this->point = $point;
    }

    public static function success(RoutePoint $point)
    {
        return new self(true, $point);
    }

    public static function fail()
    {
        return new self(false, null);
    }

    public function matched()
    {
        return $this->matched;
    }

    public function point()
    {
        return $this->point;
    }
}
