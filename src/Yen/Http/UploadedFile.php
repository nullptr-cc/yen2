<?php

namespace Yen\Http;

use Yen\Http\Contract\IUploadedFile;

class UploadedFile implements IUploadedFile
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

        if ($this->isMoved()) {
            throw new \RuntimeException('file already moved');
        };

        $dir = dirname($target_path);

        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('target dir not exists or is not dir');
        };

        if (!is_writable($dir)) {
            throw new \InvalidArgumentException('target dir is not writable');
        };

        if (!$this->move($this->path, $target_path)) {
            throw new \RuntimeException('file not moved');
        };

        $this->moved = true;

        return true;
    }

    public function isMoved()
    {
        return $this->moved;
    }

    protected function move($src, $dst)
    {
        return move_uploaded_file($src, $dst);
    }
}
