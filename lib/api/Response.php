<?php

namespace API;

class Response
{
  private $status = 200;
  private $data = array();

  public function getStatus()
  {
    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getData()
  {
    return $this->data;
  }

  /**
   * Set the entire response array.
   * Any previous calls to setData or addParam will be wiped out
   * 
   * @param mixed $data
   */
  public function setData($data)
  {
    $this->data = $data;
  }

  /**
   * Set a portion of the response array
   * 
   * @param string $key
   * @param mixed $value
   */
  public function setDataByKey($key, $value)
  {
    $this->data[$key] = $value;
  }

  /**
   * Add a single entry to the JSON params array
   * 
   * @param string $key
   * @param mixed $value
   */
  public function addParam($key, $value)
  {
    if (!array_key_exists('params', $this->data))
    {
      $this->data['params'] = array();
    }

    $this->data['params'][$key] = $value;
  }

  /**
   * 
   * @param string $error
   */
  public function addError($error)
  {
    if (!array_key_exists('errors', $this->data))
    {
      $this->data['errors'] = array();
    }

    $this->data['errors'][] = $error;
  }
}