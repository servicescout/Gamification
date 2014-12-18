<?php

namespace Test\API\Auth;

use API\Auth\Verify;
use Mockery as m;

class VerifyTest extends \Test\TestCase
{
  public function testProcess_valid()
  {
    $account = m::mock('Model\Entity\Account');
    $account->shouldReceive('toArray')->once()->andReturn('fooArray');

    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->once()->andReturn(true);
    $auth->shouldReceive('getAccount')->once()->andReturn($account);

    $api = new Verify($auth);

    $response = $api->process();

    self::assertInstanceOf('API\Response', $response);
    self::assertEquals(200, $response->getStatus());
    self::assertEquals(array(
      'params' => array('account' => 'fooArray')
    ), $response->getData());
  }
}