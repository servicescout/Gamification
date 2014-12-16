<?php

namespace API;

abstract class Post extends API
{
  private $payloadData = array();

  public function __construct($payload = null)
  {
    $this->payloadData = json_decode($payload ?: file_get_contents('php://input'), true);
  }

  public function getPayloadParameter($name, $default = null)
  {
    return array_key_exists($name, $this->payloadData)
      ? $this->payloadData[$name]
      : $default;
  }
}