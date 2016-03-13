<?php

namespace Yen\Presenter;

use Yen\Renderer\Contract\ITemplateRenderer;

class TemplatePresenter implements Contract\IPresenter, Contract\IErrorPresenter
{
    protected $renderer;

    public function __construct(ITemplateRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function present(...$args)
    {
        $template = array_shift($args);
        $params = array_shift($args);
        $content = $this->render($template, is_null($params) ? [] : $params);

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

    protected function render($template, array $params)
    {
        return $this->renderer->render($template, $params);
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
