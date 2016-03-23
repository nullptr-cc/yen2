<?php

namespace Yen\Handler;

use Yen\Http\Contract\IResponse;
use Yen\Http\Contract\IUri;
use Yen\Http\Response;
use Yen\Renderer\MimedDocument;

trait HandlerResponseHelpers
{
    protected function responseOk(MimedDocument $doc)
    {
        return $this->response(
            IResponse::STATUS_OK,
            ['Content-Type' => $doc->mime()],
            $doc->content()
        );
    }

    protected function responseError(MimedDocument $doc)
    {
        return $this->response(
            IResponse::STATUS_INTERNAL_ERROR,
            ['Content-Type' => $doc->mime()],
            $doc->content()
        );
    }

    protected function responseNotFound(MimedDocument $doc)
    {
        return $this->response(
            IResponse::STATUS_NOT_FOUND,
            ['Content-Type' => $doc->mime()],
            $doc->content()
        );
    }

    protected function responseBadRequest(MimedDocument $doc)
    {
        return $this->response(
            IResponse::STATUS_BAD_REQUEST,
            ['Content-Type' => $doc->mime()],
            $doc->content()
        );
    }

    protected function responseForbidden(MimedDocument $doc)
    {
        return $this->response(
            IResponse::STATUS_FORBIDDEN,
            ['Content-Type' => $doc->mime()],
            $doc->content()
        );
    }

    protected function redirect(IUri $url, $permanent = false)
    {
        if ($permanent) {
            $code = IResponse::STATUS_MOVED_PERMANENTLY;
        } else {
            $code = IResponse::STATUS_MOVED_TEMPORARY;
        };

        return $this->response($code, ['Location' => $url]);
    }

    protected function response($code, $headers = [], $content = '')
    {
        return new Response($code, $headers, $content);
    }
}
