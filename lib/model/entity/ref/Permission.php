<?php

namespace Model\Entity\Ref;

class Permission
{
  const ADMIN = 'Admin';
  const BANKER = 'Banker';
  const TRAINER = 'Trainer';

  public function getOpts()
  {
    $resolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();

    $unnest = $resolver->connection()->select('SELECT UNNEST(ENUM_RANGE(NULL::ref.permission))');

    $opts = array();

    foreach ($unnest as $val)
    {
      $opts[] = $val['unnest'];
    }

    return $opts;
  }
}