<?php

namespace YenTest\View;

use Yen\View\View;
use Yen\View\ViewFactory;

class CustomView extends View {};

class ViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockDC;

    public function testMake()
    {
        $dc = $this->mockDC();
        $factory = new ViewFactory($dc, '\YenTest\View\%sView');
        $view = $factory->makeView('custom');
        $this->assertInstanceOf('\YenTest\View\CustomView', $view);
    }

    public function testMakeNull()
    {
        $dc = $this->mockDC();
        $factory = new ViewFactory($dc, '\YenTest\View\%s');
        $view = $factory->makeView('fake');
        $this->assertInstanceOf('\Yen\View\DefaultView', $view);
    }
}
