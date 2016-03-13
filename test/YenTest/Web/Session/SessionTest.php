<?php

namespace YenTest\Web\Session;

use Yen\Web\Session\Session;
use Yen\Web\Session\SessionStorage;
use Yen\Http\Contract\IServerRequest;

/**
 * @requires extension runkit
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $yarn;

    public function setUp()
    {
        $this->yarn = new \Yarn\XUnit\Functional($this);
    }

    public function tearDown()
    {
        $this->yarn->unravel();
        unset($this->yarn);
    }

    public function testIsActive()
    {
        $status = $this->yarn->ravel('session_status');
        $status->expectsOnce()
               ->willReturn(PHP_SESSION_ACTIVE);

        $session = new Session();

        $this->assertTrue($session->isActive());
    }

    public function testStartHappyPath()
    {
        $status = $this->yarn->ravel('session_status');
        $status->expectsOnce()
               ->willReturn(PHP_SESSION_NONE);
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(true);

        $session = new Session();

        $this->assertTrue($session->start());
    }

    public function testAlreadyStartedException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Session already started');

        $status = $this->yarn->ravel('session_status');
        $status->expectsOnce()
               ->willReturn(PHP_SESSION_ACTIVE);

        $session = new Session();
        $session->start();
    }

    public function testCannotStartException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not start session');

        $status = $this->yarn->ravel('session_status');
        $status->expectsOnce()
               ->willReturn(PHP_SESSION_NONE);
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(false);

        $session = new Session();
        $session->start();
    }

    public function testStealedSessionIdException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Session ID from other session');

        $status = $this->yarn->ravel('session_status');
        $status->expectsOnce()
               ->willReturn(PHP_SESSION_NONE);
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(true);

        $storage = $this->prophesize(SessionStorage::class);
        $storage->has('started_at')->willReturn(true);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['getInnerStorage'])
                        ->getMock();
        $session->method('getInnerStorage')
                ->willReturn($storage->reveal());

        $session->start();
    }

    public function testResumeHappyPath()
    {
        $name = $this->yarn->ravel('session_name');
        $name->expectsOnce()
             ->willReturn('sid');
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(true);

        $request = $this->prophesize(IServerRequest::class);
        $request->getCookieParams()
                ->shouldBeCalled()
                ->willReturn(['sid' => 'foobar']);

        $storage = $this->prophesize(SessionStorage::class);
        $storage->has('started_at')
                ->willReturn(true);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive', 'getInnerStorage'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);
        $session->method('getInnerStorage')
                ->willReturn($storage->reveal());

        $this->assertTrue($session->resume($request->reveal()));
    }

    public function testResumeActive()
    {
        $request = $this->prophesize(IServerRequest::class);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(true);

        $this->assertTrue($session->resume($request->reveal()));
    }

    public function testNoCookieForResume()
    {
        $name = $this->yarn->ravel('session_name');
        $name->expectsOnce()
             ->willReturn('sid');

        $request = $this->prophesize(IServerRequest::class);
        $request->getCookieParams()
                ->shouldBeCalled()
                ->willReturn([]);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);

        $this->assertFalse($session->resume($request->reveal()));
    }

    public function testCannotResumeException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not resume session');

        $name = $this->yarn->ravel('session_name');
        $name->expectsOnce()
             ->willReturn('sid');
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(false);

        $request = $this->prophesize(IServerRequest::class);
        $request->getCookieParams()
                ->shouldBeCalled()
                ->willReturn(['sid' => 'foobar']);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);

        $session->resume($request->reveal());
    }

    public function testStopSessionWithoutStartedAt()
    {
        $name = $this->yarn->ravel('session_name');
        $name->expectsOnce()
             ->willReturn('sid');
        $start = $this->yarn->ravel('session_start');
        $start->expectsOnce()
              ->willReturn(true);

        $request = $this->prophesize(IServerRequest::class);
        $request->getCookieParams()
                ->shouldBeCalled()
                ->willReturn(['sid' => 'foobar']);

        $storage = $this->prophesize(SessionStorage::class);
        $storage->has('started_at')
                ->willReturn(false);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive', 'getInnerStorage', 'stop'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);
        $session->method('getInnerStorage')
                ->willReturn($storage->reveal());
        $session->expects($this->once())
                ->method('stop')
                ->will($this->returnSelf());

        $this->assertFalse($session->resume($request->reveal()));
    }

    public function testSuspend()
    {
        $write_close = $this->yarn->ravel('session_write_close');
        $write_close->expectsOnce()
                    ->willReturn(true);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(true);

        $this->assertTrue($session->suspend());
    }

    public function testNotSuspend()
    {
        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);

        $this->assertFalse($session->suspend());
    }

    public function testStopHappyPath()
    {
        $this->yarn
            ->ravel('session_get_cookie_params')
            ->expectsOnce()
            ->willReturn([
                'path' => '/test',
                'domain' => 'foobar.net',
                'secure' => true,
                'httponly' => true
            ]);

        $this->yarn
            ->ravel('session_name')
            ->expectsOnce()
            ->willReturn('sid');

        $this->yarn
            ->ravel('time')
            ->expectsOnce()
            ->willReturn(0);

        $this->yarn
            ->ravel('setcookie')
            ->with('sid', '', -42000, '/test', 'foobar.net', true, true)
            ->expectsOnce()
            ->willReturn(true);

        $this->yarn
            ->ravel('session_destroy')
            ->expectsOnce()
            ->willReturn(true);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(true);

        $this->assertTrue($session->stop());
    }

    public function testNoSessionToStopException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No session to stop');

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(false);

        $session->stop();
    }

    public function testCannotDestroySessionException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not destroy session');

        $this->yarn
            ->ravel('session_get_cookie_params')
            ->expectsOnce()
            ->willReturn([
                'path' => '/test',
                'domain' => 'foobar.net',
                'secure' => true,
                'httponly' => true
            ]);

        $this->yarn
            ->ravel('session_name')
            ->expectsOnce()
            ->willReturn('sid');

        $this->yarn
            ->ravel('time')
            ->expectsOnce()
            ->willReturn(0);

        $this->yarn
            ->ravel('setcookie')
            ->with('sid', '', -42000, '/test', 'foobar.net', true, true)
            ->expectsOnce()
            ->willReturn(true);

        $this->yarn
            ->ravel('session_destroy')
            ->expectsOnce()
            ->willReturn(false);

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(true);

        $session->stop();
    }

    public function testGetStorage()
    {
        $session = new Session();
        $storage = $session->getStorage('test');

        $this->assertInstanceOf(SessionStorage::class, $storage);
    }

    public function testAccessDeniesToInnerStorageException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Access denied to inner session storage');

        $session = new Session();
        $storage = $session->getStorage('__inner');
    }
}
