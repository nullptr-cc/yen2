<?php

namespace YenMock\Handler;

use Yen\Handler\Contract\IHandler;
use Yen\Handler\HandlerResponseHelpers;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Uri;
use Yen\Renderer\MimedDocument;

class HelpersHandler implements IHandler
{
    use HandlerResponseHelpers;

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_GET];
    }

    public function handle(IServerRequest $request)
    {
        $doc = MimedDocument::createText('test');
        $uri = Uri::createFromString('/test');

        switch ($request->getQueryParams()['r']) {
            case 'ok':
                return $this->responseOk($doc);
                break;
            case 'bad_request':
                return $this->responseBadRequest($doc);
                break;
            case 'forbidden':
                return $this->responseForbidden($doc);
                break;
            case 'not_found':
                return $this->responseNotFound($doc);
                break;
            case 'redirect_perm':
                return $this->redirect($uri, true);
                break;
            case 'redirect_temp':
                return $this->redirect($uri, false);
                break;
            case 'error':
                return $this->responseError($doc);
                break;
            default:
                throw new \LogicException('Undefined');
                break;
        };
    }
}
