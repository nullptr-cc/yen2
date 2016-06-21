<?php

namespace YenTest\Router;

use Yen\Router\MatchResult;
use Yen\Router\RoutePoint;

class MatchResultTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $result = MatchResult::success(new RoutePoint('/test', ['foo' => 'bar']));

        $this->assertTrue($result->matched());
        $this->assertEquals('/test', $result->point()->path());
        $this->assertEquals(['foo' => 'bar'], $result->point()->arguments());
    }

    public function testFail()
    {
        $result = MatchResult::fail();

        $this->assertFalse($result->matched());
    }
}
