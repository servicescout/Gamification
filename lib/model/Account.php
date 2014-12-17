<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $table = 'public.account';
  public function getDates() { return array(); }

  public function toArray()
  {
    $con = self::getConnectionResolver()->connection();
    $json = $con->selectOne('SELECT ARRAY_TO_JSON(permissions) AS permissions FROM account WHERE id = :id', array(
      'id' => $this->id,
    ));

    return array_merge(parent::toArray(), array(
      'permissions' => json_decode($json['permissions'], true),
    ));
  }
}