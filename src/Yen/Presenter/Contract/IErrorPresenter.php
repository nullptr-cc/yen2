<?php

namespace Yen\Presenter\Contract;

interface IErrorPresenter
{
    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function errorInternal();

    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function errorNotFound();

    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function errorForbidden();

    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function errorInvalidParams();

    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function errorInvalidMethod();
}
