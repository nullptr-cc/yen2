<?php

namespace YenTest\Renderer;

use Yen\Renderer\TemplateRenderer;
use Yen\Util\Contract\IPluginRegistry;

class TemplateRendererTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockSettings;

    public function testRender()
    {
        $container = new \MicroVFS\Container();
        $container->set('tpl/layout.tpl', 'head -- <?= $main_content ?> -- tail');
        $container->set('tpl/test.tpl', 'content: foo = <?= $foo ?>');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $settings = $this->mockSettings(['path' => 'vfs://tpl'], ['ext' => ['.tpl', '.tpl']]);

        $renderer = new TemplateRenderer($settings);

        $body = $renderer->render('test', ['foo' => 'baz']);
        $this->assertEquals('content: foo = baz', $body);

        $layout = $renderer->render('layout', ['main_content' => $body]);
        $this->assertEquals('head -- content: foo = baz -- tail', $layout);
    }

    public function testPartial()
    {
        $container = new \MicroVFS\Container();
        $container->set('tpl/test.tpl', 'content: foo = <?= $this->render("foo", ["x" => 5]) ?>');
        $container->set('tpl/foo.tpl', '"x: <?= $x ?>"');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $settings = $this->mockSettings(['path' => 'vfs://tpl'], ['ext' => ['.tpl', '.tpl']]);

        $renderer = new TemplateRenderer($settings);
        $body = $renderer->render('test', []);
        $this->assertEquals('content: foo = "x: 5"', $body);
    }

    public function testPluginCall()
    {
        $plugins = $this->prophesize(IPluginRegistry::class);
        $plugins
            ->getPlugin('escape')
            ->shouldBeCalled()
            ->willReturn('htmlspecialchars');

        $renderer = new TemplateRenderer($this->mockSettings(), $plugins->reveal());
        $escaped = $renderer->escape('<b>Foo</b>');
        $this->assertEquals('&lt;b&gt;Foo&lt;/b&gt;', $escaped);
    }

    public function testUnallowablePluginCallException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Plugin call is unallowable: no plugin registry');

        $renderer = new TemplateRenderer($this->mockSettings());

        $renderer->escape();
    }
}
