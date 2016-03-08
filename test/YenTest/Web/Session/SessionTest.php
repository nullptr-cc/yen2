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
    public function testIsActive()
    {
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_status',
            function () {
                return PHP_SESSION_ACTIVE;
            }
        );

        $session = new Session();

        $this->assertTrue($session->isActive());
    }

    public function testStartHappyPath()
    {
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_status',
            function () {
                return PHP_SESSION_NONE;
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return true;
            }
        );

        $session = new Session();

        $this->assertTrue($session->start());
    }

    public function testAlreadyStartedException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Session already started');

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_status',
            function () {
                return PHP_SESSION_ACTIVE;
            }
        );

        $session = new Session();
        $session->start();
    }

    public function testCannotStartException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not start session');

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_status',
            function () {
                return PHP_SESSION_NONE;
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return false;
            }
        );

        $session = new Session();
        $session->start();
    }

    public function testStealedSessionIdException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Session ID from other session');

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_status',
            function () {
                return PHP_SESSION_NONE;
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return true;
            }
        );

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
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return true;
            }
        );

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
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );

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

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return false;
            }
        );

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
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );
        $mocker->mockFunc(
            'session_start',
            function () {
                return true;
            }
        );

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
        $wc_called = 0;

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_write_close',
            function () use (&$wc_called) {
                ++$wc_called;
                return true;
            }
        );

        $session = $this->getMockBuilder(Session::class)
                        ->setMethods(['isActive'])
                        ->getMock();
        $session->method('isActive')
                ->willReturn(true);

        $this->assertTrue($session->suspend());
        $this->assertEquals(1, $wc_called);
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
        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_get_cookie_params',
            function () {
                return [
                    'path' => '/test',
                    'domain' => 'foobar.net',
                    'secure' => true,
                    'httponly' => true
                ];
            }
        );
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );
        $mocker->mockFunc(
            'time',
            function () {
                return 0;
            }
        );
        $mocker->mockFunc(
            'setcookie',
            function ($name, $value, $expire, $path, $domain, $secure, $httponly) {
                $this->assertEquals('sid', $name);
                $this->assertEquals('', $value);
                $this->assertEquals(-42000, $expire);
                $this->assertEquals('/test', $path);
                $this->assertEquals('foobar.net', $domain);
                $this->assertTrue($secure);
                $this->assertTrue($httponly);
                return true;
            }
        );
        $mocker->mockFunc(
            'session_destroy',
            function () {
                return true;
            }
        );

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

        $mocker = new \FuncMock\Mocker();
        $mocker->mockFunc(
            'session_get_cookie_params',
            function () {
                return [
                    'path' => '/test',
                    'domain' => 'foobar.net',
                    'secure' => true,
                    'httponly' => true
                ];
            }
        );
        $mocker->mockFunc(
            'session_name',
            function () {
                return 'sid';
            }
        );
        $mocker->mockFunc(
            'time',
            function () {
                return 0;
            }
        );
        $mocker->mockFunc(
            'setcookie',
            function ($name, $value, $expire, $path, $domain, $secure, $httponly) {
                $this->assertEquals('sid', $name);
                $this->assertEquals('', $value);
                $this->assertEquals(-42000, $expire);
                $this->assertEquals('/test', $path);
                $this->assertEquals('foobar.net', $domain);
                $this->assertTrue($secure);
                $this->assertTrue($httponly);
                return true;
            }
        );
        $mocker->mockFunc(
            'session_destroy',
            function () {
                return false;
            }
        );

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
