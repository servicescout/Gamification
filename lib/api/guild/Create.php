<?php

namespace API\Guild;

class Create extends \API\Post
{
  public function execute()
  {
    $guild = new \Model\Guild();
    $guild->name = $this->getPayloadParameter('name');
    $guild->save();

    $this->addParam('guild', $guild->toArray());
  }
}