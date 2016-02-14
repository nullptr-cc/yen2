<?php

namespace YenTest\View;

use Yen\View;

class ViewRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetView()
    {
        $factory = new View\ViewFactory('\\YenMock\\View\\%sView');
        $registry = new View\ViewRegistry($factory);

        $view = $registry->getView('custom');

        $this->assertInstanceOf(\YenMock\View\CustomView::class, $view);
    }
}
