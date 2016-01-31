<?php

namespace YenTest\View;

use Yen\View\View;
use Yen\View\ViewFactory;

class ViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $factory = new ViewFactory('\YenMock\View\%sView');
        $view = $factory->makeView('custom');
        $this->assertInstanceOf('\YenMock\View\CustomView', $view);
        $view = $factory->make('custom');
        $this->assertInstanceOf('\YenMock\View\CustomView', $view);
    }

    public function testMakeNull()
    {
        $factory = new ViewFactory('\YenMock\View\%s');
        $view = $factory->makeView('fake');
        $this->assertInstanceOf('\Yen\View\DefaultView', $view);
        $view = $factory->make('fake');
        $this->assertInstanceOf('\Yen\View\DefaultView', $view);
    }

    public function testCanMake()
    {
        $factory = new ViewFactory('\YenMock\View\%sView');
        $this->assertTrue($factory->canMake('custom'));
        $this->assertTrue($factory->canMake('fake'));
    }
}
