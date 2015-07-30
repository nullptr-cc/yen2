<?php

namespace Yen\Renderer;

class TemplateRenderer implements Contract\IRenderer
{
    protected $tpl_dir;
    protected $tpl_ext;

    public function __construct($tpl_dir, $tpl_ext = '.tpl')
    {
        $this->tpl_dir = $tpl_dir;
        $this->tpl_ext = $tpl_ext;
    }

    public function mime()
    {
        return 'text/plain';
    }

    public function render($data, ...$args)
    {
        if (!isset($args[0])) {
            throw new \InvalidArgumentException('Missed start template');
        };

        $start_tpl = $args[0];
        $layout = isset($args[1]) ? $args[1] : null;

        $headers = ['Content-Type' => $this->mime()];

        $body = $this->fetch($data, $start_tpl);
        if ($layout) {
            $body = $this->fetch(['main_content' => $body], $layout);
        };

        return [$headers, $body];
    }

    protected function fetch()
    {
        extract(func_get_arg(0));
        ob_start();
        include $this->resolveTplPath(func_get_arg(1));
        return ob_get_clean();
    }

    protected function resolveTplPath($tpl)
    {
        return sprintf('%s/%s%s', $this->tpl_dir, $tpl, $this->tpl_ext);
    }
}
