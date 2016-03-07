<?php

namespace Yen\Presenter\Contract;

interface IPresenter
{
    /**
     * @return Yen\Http\Contract\IResponse
     */
    public function present();
}
