<?php

namespace Test\API\Auth;

use API\Auth\Logout;
use Mockery as m;

class LogoutTest extends \Test\TestCase
{
  public function testProcess()
  {
    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->once()->andReturn(true);
    $auth->shouldReceive('getSession->destroy')->once();

    $api = new Logout($auth);

    $response = $api->process();

    self::assertInstanceOf('API\Response', $response);
    self::assertEquals(200, $response->getStatus());
    self::assertEquals(array(), $response->getData());
  }
}