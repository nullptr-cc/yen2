<?php

namespace YenTest\Renderer;

use Yen\Renderer\HtmlTemplateRenderer;
use Yen\Settings\Contract\ISettings;

class HtmlTemplateRendererTest extends \PHPUnit_Framework_TestCase
{
    use \YenMock\MockSettings;

    public function test()
    {
        $container = new \MicroVFS\Container();
        $container->set('tpl/test.tpl', 'content: foo = <?= $foo ?>');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $settings = $this->mockSettings(['path' => 'vfs://tpl'], ['ext' => ['.tpl', '.tpl']]);

        $renderer = new HtmlTemplateRenderer($settings);

        $doc = $renderer->render('test', ['foo' => 'baz']);
        $this->assertEquals('text/html', $doc->mime());
        $this->assertEquals('content: foo = baz', $doc->content());
    }
}
