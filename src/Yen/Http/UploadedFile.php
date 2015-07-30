<?php

namespace Yen\Http;

class UploadedFile
{
    protected $error;
    protected $size;
    protected $name;
    protected $mime;
    protected $path;
    protected $moved;

    public function __construct($error, $size, $name, $mime, $path)
    {
        $this->error = $error;
        $this->size = $size;
        $this->name = $name;
        $this->mime = $mime;
        $this->path = $path;
        $this->moved = false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getClientFilename()
    {
        return $this->name;
    }

    public function getClientMediaType()
    {
        return $this->mime;
    }

    public function moveTo($target_path)
    {
        if ($this->path === null) {
            throw new \RuntimeException('file not uploaded');
        };

        if ($this->moved) {
            throw new \RuntimeException('file already moved');
        };

        $dir = dirname($target_path);

        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('target dir not exists or is not dir');
        };

        if (!is_writable($dir)) {
            throw new \InvalidArgumentException('target dir is not writable');
        };

        if (PHP_SAPI == 'cli') {
            if (!rename($this->path, $target_path)) {
                throw new \RuntimeException('file not moved');
            };
        } else {
            if (!move_uploaded_file($this->path, $target_path)) {
                throw new \RuntimeException('file not moved');
            };
        };

        $this->moved = true;

        return true;
    }
}
