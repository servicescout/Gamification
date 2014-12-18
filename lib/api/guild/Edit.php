<?php

namespace API\Guild;

class Edit extends \API\API
{
  private $guildId;

  public function __construct($auth, $guildId)
  {
    parent::__construct($auth);

    $this->guildId = $guildId;
  }

  public function execute(&$response)
  {
    $guild = \Model\Entity\Guild::find($this->guildId);
    $guild->name = $this->getPayloadParameter('name');
    $guild->save();

    $response->addParam('guild', $guild->toArray());
  }
}