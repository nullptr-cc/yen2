<?php

namespace Yen\Renderer;

use Yen\Settings\Contract\ISettings;
use Yen\Util\Contract\IPluginRegistry;
use Yen\Renderer\Contract\ITemplateRenderer;

class TemplateRenderer implements ITemplateRenderer
{
    protected $tpl_dir;
    protected $tpl_ext;
    protected $plugins;

    public function __construct(ISettings $settings, IPluginRegistry $plugins = null)
    {
        $this->tpl_dir = $settings->get('path');
        $this->tpl_ext = $settings->lookup('ext', '.tpl');
        $this->plugins = $plugins;
    }

    public function __call($method, $args)
    {
        if ($this->plugins === null) {
            throw new \LogicException('Plugin call is unallowable: no plugin registry');
        };

        $plugin = $this->plugins->getPlugin($method);
        return $plugin(...$args);
    }

    public function render($template, $data)
    {
        $content = $this->fetch($this->resolveTplPath($template), $data);
        return $this->createDocument($content);
    }

    protected function fetch()
    {
        extract(func_get_arg(1));
        ob_start();
        include func_get_arg(0);
        return ob_get_clean();
    }

    protected function resolveTplPath($tpl)
    {
        return sprintf('%s/%s%s', $this->tpl_dir, $tpl, $this->tpl_ext);
    }

    protected function createDocument($content)
    {
        return MimedDocument::createText($content);
    }
}
