<?php

namespace YenTest\Session;

use Yen\Session\Session;
use Yen\Session\SessionStorage;
use Yen\Http\Contract\IServerRequest;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $defunc;

    public function setUp()
    {
        $this->defunc = (new \Defunc\Builder())->in('Yen\Session');
    }

    public function tearDown()
    {
        $this->defunc->clear();
    }

    public function testIsActive()
    {
        $this->defunc->session_status()
                     ->willReturn(PHP_SESSION_ACTIVE);

        $session = new Session();

        $this->assertTrue($session->isActive());
    }

    public function testStartHappyPath()
    {
        $this->defunc->session_status()
                     ->willReturn(PHP_SESSION_NONE);
        $this->defunc->session_start()
                     ->willReturn(true);

        $session = new Session();

        $this->assertTrue($session->start());
    }

    public function testAlreadyStartedException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Session already started');

        $this->defunc->session_status()
                     ->willReturn(PHP_SESSION_ACTIVE);

        $session = new Session();
        $session->start();
    }

    public function testCannotStartException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not start session');

        $this->defunc->session_status()
                     ->willReturn(PHP_SESSION_NONE);
        $this->defunc->session_start()
                     ->willReturn(false);

        $session = new Session();
        $session->start();
    }

    public function testStealedSessionIdException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Session ID from other session');

        $this->defunc->session_status()
                     ->willReturn(PHP_SESSION_NONE);
        $this->defunc->session_start()
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
        $this->defunc->session_name()
                     ->willReturn('sid');
        $this->defunc->session_start()
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
        $this->defunc->session_name()
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

        $this->defunc->session_name()
                     ->willReturn('sid');
        $this->defunc->session_start()
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
        $this->defunc->session_name()
                     ->willReturn('sid');
        $this->defunc->session_start()
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
        $this->defunc->session_write_close()
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
        $this->defunc
             ->session_get_cookie_params()
             ->willReturn([
                 'path' => '/test',
                 'domain' => 'foobar.net',
                 'secure' => true,
                 'httponly' => true
             ]);

        $this->defunc
             ->session_name()
             ->willReturn('sid');

        $this->defunc
             ->time()
             ->willReturn(0);

        $this->defunc
             ->setcookie('sid', '', -42000, '/test', 'foobar.net', true, true)
             ->willReturn(true);

        $this->defunc
             ->session_destroy()
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

        $this->defunc
             ->session_get_cookie_params()
             ->willReturn([
                 'path' => '/test',
                 'domain' => 'foobar.net',
                 'secure' => true,
                 'httponly' => true
             ]);

        $this->defunc
             ->session_name()
             ->willReturn('sid');

        $this->defunc
             ->time()
             ->willReturn(0);

        $this->defunc
             ->setcookie('sid', '', -42000, '/test', 'foobar.net', true, true)
             ->willReturn(true);

        $this->defunc
             ->session_destroy()
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
