<?php

namespace API\Guild;

class Create extends \API\API
{
  public function execute(&$response)
  {
    $guild = new \Model\Entity\Guild();
    $guild->name = $this->getPayloadParameter('name');
    $guild->save();

    $response->addParam('guild', $guild->toArray());
  }
}