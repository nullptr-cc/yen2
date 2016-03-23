<?php

namespace Yen\Renderer;

final class MimedDocument
{
    private $content;
    private $mime;

    public function __construct($content, $mime)
    {
        $this->content = $content;
        $this->mime = $mime;
    }

    public static function createText($content)
    {
        return new self($content, 'text/plain');
    }

    public static function createHtml($content)
    {
        return new self($content, 'text/html');
    }

    public static function createXml($content)
    {
        return new self($content, 'text/xml');
    }

    public static function createJson($content)
    {
        return new self($content, 'application/json');
    }

    public function content()
    {
        return $this->content;
    }

    public function mime()
    {
        return $this->mime;
    }

    public function __toString()
    {
        return $this->content();
    }
}
