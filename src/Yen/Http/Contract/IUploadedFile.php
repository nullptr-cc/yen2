<?php

namespace Yen\Http\Contract;

interface IUploadedFile
{
    public function moveTo($targetPath);
    public function getSize();
    public function getError();
    public function getClientFilename();
    public function getClientMediaType();
}
