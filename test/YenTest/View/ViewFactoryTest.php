<?php

namespace YenTest\View;

use Yen\View\View;
use Yen\View\ViewFactory;

class CustomView extends View {};

class ViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $dc = new \Yen\Core\DC();
        $factory = new ViewFactory($dc, '\YenTest\View\%sView');
        $view = $factory->make('custom');
        $this->assertInstanceOf('\YenTest\View\CustomView', $view);
    }

    public function testMakeNull()
    {
        $dc = new \Yen\Core\DC();
        $factory = new ViewFactory($dc, '\YenTest\View\%s');
        $view = $factory->make('fake');
        $this->assertInstanceOf('\Yen\View\NullView', $view);
    }
}
