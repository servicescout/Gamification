<?php

namespace Model\Entity\Ref;

class CharacterClass
{
  public function getOpts()
  {
    $resolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();

    $unnest = $resolver->connection()->select('SELECT UNNEST(ENUM_RANGE(NULL::ref.character_class))');

    $opts = array();

    foreach ($unnest as $val)
    {
      $opts[] = $val['unnest'];
    }

    return $opts;
  }
}