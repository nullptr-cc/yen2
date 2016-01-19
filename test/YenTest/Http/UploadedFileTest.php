<?php

namespace YenTest\Http;

use Yen\Http;

class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $ufile = new Http\UploadedFile(UPLOAD_ERR_OK, 1, 'test.txt', 'text/plain', 'vfs://tmp-test.txt');
        $this->assertEquals(UPLOAD_ERR_OK, $ufile->getError());
        $this->assertEquals(1, $ufile->getSize());
        $this->assertEquals('test.txt', $ufile->getClientFilename());
        $this->assertEquals('text/plain', $ufile->getClientMediaType());
        $this->assertFalse($ufile->isMoved());
    }

    public function testMoveTo()
    {
        $container = new \MicroVFS\Container();
        $container->set('tmp/xxxxx.tmp', 'test text');
        $container->setDir('target');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $ufile = $this->getMockBuilder('\Yen\Http\UploadedFile')
                      ->setConstructorArgs([UPLOAD_ERR_OK, 9, 'test.txt', 'text/plain', 'vfs://tmp/xxxxx.tmp'])
                      ->setMethods(['move'])
                      ->getMock();
        $ufile->method('move')->willReturn(true);

        $moved = $ufile->moveTo('vfs://target/test.txt');
        $this->assertTrue($moved);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage file not uploaded
     */
    public function testMoveToExcFileNotUploaded()
    {
        $ufile = new Http\UploadedFile(null, null, null, null, null);
        $ufile->moveTo('vfs://tmp');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage file already moved
     */
    public function testMoveToExcFileAlreadyMoved()
    {
        $ufile = $this->getMockBuilder('\Yen\Http\UploadedFile')
                      ->setConstructorArgs([UPLOAD_ERR_OK, 9, 'test.txt', 'text/plain', 'vfs://tmp/xxxxx.tmp'])
                      ->setMethods(['isMoved'])
                      ->getMock();
        $ufile->method('isMoved')->willReturn(true);
        $ufile->moveTo('vfs://tmp');
    }


    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage target dir not exists or is not dir
     */
    public function testMoveToExcInvalidTargetDir()
    {
        $container = new \MicroVFS\Container();
        $container->set('tmp/xxxxx.tmp', 'test text');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $ufile = new Http\UploadedFile(UPLOAD_ERR_OK, 9, 'test.txt', 'text/plain', 'vfs://tmp/xxxxx.tmp');
        $ufile->moveTo('vfs://tmp');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage target dir is not writable
     */
    public function testMoveToExcTargetDirIsNotWritable()
    {
        $container = new \MicroVFS\Container();
        $container->set('tmp/xxxxx.tmp', 'test text');
        $container->setDir('target', false);
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $ufile = new Http\UploadedFile(UPLOAD_ERR_OK, 9, 'test.txt', 'text/plain', 'vfs://tmp/xxxxx.tmp');
        $ufile->moveTo('vfs://target/test.txt');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage file not moved
     */
    public function testMoveToExcFileNotMoved()
    {
        $container = new \MicroVFS\Container();
        $container->set('tmp/xxxxx.tmp', 'test text');
        $container->setDir('target');
        \MicroVFS\StreamWrapper::unregister('vfs');
        \MicroVFS\StreamWrapper::register('vfs', $container);

        $ufile = $this->getMockBuilder('\Yen\Http\UploadedFile')
                      ->setConstructorArgs([UPLOAD_ERR_OK, 9, 'test.txt', 'text/plain', 'vfs://tmp/xxxxx.tmp'])
                      ->setMethods(['move'])
                      ->getMock();
        $ufile->method('move')->willReturn(false);

        $ufile->moveTo('vfs://target/test.txt');
    }
}
