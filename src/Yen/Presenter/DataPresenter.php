<?php

namespace Yen\Presenter;

use Yen\Renderer\Contract\IDataRenderer;

class DataPresenter implements Contract\IPresenter, Contract\IErrorPresenter
{
    protected $renderer;

    public function __construct(IDataRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function present($data = null)
    {
        return $this->response(200, $data);
    }

    public function errorInternal($data = null)
    {
        return $this->response(500, $data);
    }

    public function errorNotFound($data = null)
    {
        return $this->response(404, $data);
    }

    public function errorForbidden($data = null)
    {
        return $this->response(403, $data);
    }

    public function errorInvalidParams($data = null)
    {
        return $this->response(400, $data);
    }

    public function errorInvalidMethod($data = null)
    {
        return $this->response(405, $data);
    }

    protected function response($code, $data)
    {
        return new \Yen\Http\Response(
            $code,
            ['Content-Type' => $this->renderer->mime()],
            $data ? $this->renderer->render($data) : ''
        );
    }
}
