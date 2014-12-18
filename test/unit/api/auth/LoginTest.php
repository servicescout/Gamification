<?php

namespace Test\API\Auth;

use API\Auth\Login;
use Mockery as m;

class LoginTest extends \Test\TestCase
{
  public function testProcess_valid()
  {
    $account = m::mock('Model\Entity\Account');
    $account->shouldReceive('getAttribute')->once()->with('password_hash')->andReturn('fooPasswordHash');
    $account->shouldReceive('getAttribute')->once()->with('id')->andReturn('fooId');
    $account->shouldReceive('toArray')->once()->andReturn('fooArray');

    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->once()->andReturn(true);
    $auth->shouldReceive('getSession->setValue')->once()->with('auth', array(
      'accountId' => 'fooId',
    ));

    $hasher = m::mock('Phpass\Hash');
    $hasher->shouldReceive('checkPassword')->once()
      ->with('fooPassword', 'fooPasswordHash')->andReturn(true);

    $retriever = m::mock('Model\Retriever');
    $retriever->shouldReceive('get->where->first')->once()->andReturn($account);

    $api = new Login($auth, $hasher, $retriever, json_encode(array(
      'email' => 'fooEmail',
      'password' => 'fooPassword',
    )));

    $response = $api->process();

    self::assertInstanceOf('API\Response', $response);
    self::assertEquals(200, $response->getStatus());
    self::assertEquals(array(
      'params' => array('account' => 'fooArray')
    ), $response->getData());
  }

  public function testProcess_invalidEmail()
  {
    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->once()->andReturn(true);

    $hasher = m::mock('Phpass\Hash');

    $retriever = m::mock('Model\Retriever');
    $retriever->shouldReceive('get->where->first')->once()->andReturn(null);

    $api = new Login($auth, $hasher, $retriever, json_encode(array(
      'email' => 'fooEmail',
      'password' => 'fooPassword',
    )));

    $response = $api->process();

    self::assertInstanceOf('API\Response', $response);
    self::assertEquals(400, $response->getStatus());
    self::assertEquals(array(
      'errors' => array('Invalid email or password')
    ), $response->getData());
  }

  public function testProcess_invalidPassword()
  {
    $account = m::mock('Model\Entity\Account');
    $account->shouldReceive('getAttribute')->once()->with('password_hash')->andReturn('fooPasswordHash');

    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->once()->andReturn(true);

    $hasher = m::mock('Phpass\Hash');
    $hasher->shouldReceive('checkPassword')->once()
      ->with('fooPassword', 'fooPasswordHash')->andReturn(false);

    $retriever = m::mock('Model\Retriever');
    $retriever->shouldReceive('get->where->first')->once()->andReturn($account);

    $api = new Login($auth, $hasher, $retriever, json_encode(array(
      'email' => 'fooEmail',
      'password' => 'fooPassword',
    )));

    $response = $api->process();

    self::assertInstanceOf('API\Response', $response);
    self::assertEquals(400, $response->getStatus());
    self::assertEquals(array(
      'errors' => array('Invalid email or password')
    ), $response->getData());
  }
}