<?php

namespace API;

abstract class API
{
  private $responseData = array();

  abstract public function execute();

  public function getData()
  {
    return $this->responseData;
  }

  /**
   * Set the entire response array.
   * Any previous calls to setData or addParam will be wiped out
   * 
   * @param mixed $data
   */
  public function setData($data)
  {
    $this->responseData = $data;
  }

  /**
   * Set a portion of the response array
   * 
   * @param string $key
   * @param mixed $value
   */
  public function setDataByKey($key, $value)
  {
    $this->responseData[$key] = $value;
  }

  /**
   * Add a single entry to the JSON params array
   * 
   * @param string $key
   * @param mixed $value
   */
  public function addParam($key, $value)
  {
    if (!array_key_exists('params', $this->responseData))
    {
      $this->responseData['params'] = array();
    }

    $this->responseData['params'][$key] = $value;
  }
}