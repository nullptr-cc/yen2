<?php

namespace YenTest\View;

use Yen\View\View;
use Yen\View\ViewFactory;

class ViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;

    public function testMake()
    {
        $dc = $this->mockDC();
        $factory = new ViewFactory($dc, '\YenMock\View\%sView');
        $view = $factory->makeView('custom');
        $this->assertInstanceOf('\YenMock\View\CustomView', $view);
        $view = $factory->make('custom');
        $this->assertInstanceOf('\YenMock\View\CustomView', $view);
    }

    public function testMakeNull()
    {
        $dc = $this->mockDC();
        $factory = new ViewFactory($dc, '\YenMock\View\%s');
        $view = $factory->makeView('fake');
        $this->assertInstanceOf('\Yen\View\DefaultView', $view);
        $view = $factory->make('fake');
        $this->assertInstanceOf('\Yen\View\DefaultView', $view);
    }

    public function testCanMake()
    {
        $dc = $this->mockDC();
        $factory = new ViewFactory($dc, '\YenMock\View\%sView');
        $this->assertTrue($factory->canMake('custom'));
        $this->assertTrue($factory->canMake('fake'));
    }
}
