<?php

namespace API\CharacterClass;

class ListAPI extends \API\API
{
  public function execute(&$response)
  {
    $class = new \Model\Ref\CharacterClass();
    $response->addParam('classes', $class->getOpts());
  }
}