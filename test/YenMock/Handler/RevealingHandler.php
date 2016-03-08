<?php

namespace YenMock\Handler;

class RevealingHandler extends \Yen\Handler\Handler
{
    protected $presenter;
    protected $error_presenter;

    public function __construct($presenter, $error_presenter)
    {
        $this->presenter = $presenter;
        $this->error_presenter = $error_presenter;
    }

    public function ok(...$args)
    {
        return parent::ok(...$args);
    }

    public function badParams(...$args)
    {
        return parent::badParams(...$args);
    }

    public function forbidden(...$args)
    {
        return parent::forbidden(...$args);
    }

    public function notFound(...$args)
    {
        return parent::notFound(...$args);
    }

    public function error(...$args)
    {
        return parent::error(...$args);
    }

    protected function getPresenter()
    {
        return $this->presenter;
    }

    protected function getErrorPresenter()
    {
        return $this->error_presenter;
    }
}
