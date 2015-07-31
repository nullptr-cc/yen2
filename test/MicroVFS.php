<?php

namespace MicroVFS;

class Container
{
    protected $fs = [];

    public function set($path, $content)
    {
        $this->fs[$path] = (object)[
            'content' => $content,
            'size' => strlen($content)
        ];
    }

    public function has($path)
    {
        return array_key_exists($path, $this->fs);
    }

    public function sizeOf($path)
    {
        if (!isset($this->fs[$path])) {
            throw new \RuntimeException('Cannot read '.$path);
        };

        return $this->fs[$path]->size;
    }

    public function read($path, $pos, $count)
    {
        if (!isset($this->fs[$path])) {
            throw new \RuntimeException('Cannot read '.$path);
        };

        return substr($this->fs[$path]->content, $pos, $count);
    }
}

class StreamWrapper
{
    protected static $containers;

    protected $opened;

    public static function register($scheme, $container)
    {
        if (!stream_wrapper_register($scheme, static::class)) {
            throw new \RuntimeException('Cannot register stream wrapper');
        };

        self::$containers[$scheme] = $container;
    }

    public static function unregister($scheme)
    {
        if (!stream_wrapper_unregister($scheme)) {
            throw new \RuntimeException('Cannot unregister stream wrapper');
        };
        
        unset(self::$containers[$scheme]);
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        list($scheme, $uri) = explode('://', $path, 2);
        $container = self::$containers[$scheme];

        $this->opened = (object)[
            'container' => $container,
            'path' => $uri,
            'pos' => 0,
            'size' => $container->sizeOf($uri)
        ];
        return true;
    }

    public function stream_close()
    {
        $this->opened = null;
    }

    public function stream_read($count)
    {
        $content = $this->opened->container->read($this->opened->path, $this->opened->pos, $count);
        $this->opened->pos += $count;
        if ($this->opened->pos >= $this->opened->size) {
            $this->opened->pos = $this->opened->size;
        };
        return $content;
    }

    public function stream_eof()
    {
        return $this->opened->pos == $this->opened->size;
    }

    public function stream_stat()
    {
        return [
            'mode' => 0666,
            'size' => $this->opened->size,
            'atime' => time(),
            'mtime' => time()
        ];
    }

    public function url_stat($path, $flags)
    {
        list($scheme, $uri) = explode('://', $path, 2);
        if (!self::$containers[$scheme]->has($uri)) {
            return null;
        };

        return [
            'mode' => 0666,
            'size' => self::$containers[$scheme]->sizeOf($uri),
            'atime' => time(),
            'mtime' => time()
        ];
    }
}
