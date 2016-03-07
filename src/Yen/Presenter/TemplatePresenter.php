<?php

namespace Yen\Presenter;

use Yen\Renderer\Contract\ITemplateRenderer;
use Yen\Presenter\Contract\IComponentRegistry;

class TemplatePresenter implements Contract\IPresenter, Contract\IErrorPresenter
{
    protected $renderer;
    protected $components;

    public function __construct(ITemplateRenderer $renderer, IComponentRegistry $components)
    {
        $this->renderer = $renderer;
        $this->components = $components;
    }

    public function present(...$args)
    {
        $cname = array_shift($args);
        $content = $this->render($cname, $args);

        return $this->response(200, $content);
    }

    public function errorInternal()
    {
        return $this->response(500);
    }

    public function errorNotFound()
    {
        return $this->response(404);
    }

    public function errorForbidden()
    {
        return $this->response(403);
    }

    public function errorInvalidParams()
    {
        return $this->response(400);
    }

    public function errorInvalidMethod()
    {
        return $this->response(405);
    }

    protected function render($cname, array $args)
    {
        $component = $this->components->getComponent($cname);
        return $component(...$args);
    }

    protected function response($code, $content = '')
    {
        return new \Yen\Http\Response(
            $code,
            ['Content-Type' => $this->renderer->mime()],
            $content
        );
    }
}
