<?php

namespace API\CharacterClass;

class ListAPI extends \API\API
{
  public function execute()
  {
    $class = new \Model\Ref\CharacterClass();
    $this->addParam('classes', $class->getOpts());
  }
}