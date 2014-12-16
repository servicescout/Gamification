<?php

namespace API\Guild;

class Edit extends \API\Post
{
  private $guildId;

  public function __construct($guildId)
  {
    parent::__construct();

    $this->guildId = $guildId;
  }

  public function execute()
  {
    $guild = \Model\Guild::find($this->guildId);
    $guild->name = $this->getPayloadParameter('name');
    $guild->save();

    $this->addParam('guild', $guild->toArray());
  }
}