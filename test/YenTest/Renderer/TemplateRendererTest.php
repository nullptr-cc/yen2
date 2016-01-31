<?php

namespace YenTest\Renderer;

use Yen\Renderer\TemplateRenderer;

class TemplateRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testMime()
    {
        $renderer = new TemplateRenderer(null);
        $this->assertEquals('text/plain', $renderer->mime());
    }

    public function testRender()
    {
        $container = new \MicroVFS\Container();
        $container->set('tpl/layout.tpl', 'head -- <?= $main_content ?> -- tail');
        $container->set('tpl/test.tpl', 'content: foo = <?= $foo ?>');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $renderer = new TemplateRenderer('vfs://tpl');

        list($headers, $body) = $renderer->render(['foo' => 'bar'], 'test', 'layout');
        $this->assertEquals(['Content-Type' => 'text/plain'], $headers);
        $this->assertEquals('head -- content: foo = bar -- tail', $body);

        list($headers, $body) = $renderer->render(['foo' => 'baz'], 'test');
        $this->assertEquals(['Content-Type' => 'text/plain'], $headers);
        $this->assertEquals('content: foo = baz', $body);
    }

    public function testPartial()
    {
        $container = new \MicroVFS\Container();
        $container->set('tpl/test.tpl', 'content: foo = <?= $this->partial("foo", ["x" => 5]) ?>');
        $container->set('tpl/foo.tpl', '"x: <?= $x ?>"');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $renderer = new TemplateRenderer('vfs://tpl');
        list($headers, $body) = $renderer->render([], 'test');
        $this->assertEquals(['Content-Type' => 'text/plain'], $headers);
        $this->assertEquals('content: foo = "x: 5"', $body);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missed start template
     */
    public function testRenderMissedTemplate()
    {
        $renderer = new TemplateRenderer(null);
        $renderer->render([]);
    }
}
