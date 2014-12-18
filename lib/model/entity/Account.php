<?php

namespace Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $table = 'public.account';
  public function getDates() { return array(); }

  public function player()
  {
    return $this->hasOne('Model\Entity\Player', 'account_id');
  }

  public function getPermissions()
  {
    $con = self::getConnectionResolver()->connection();
    $json = $con->selectOne('SELECT ARRAY_TO_JSON(permissions) AS permissions FROM account WHERE id = :id', array(
      'id' => $this->id,
    ));

    return json_decode($json['permissions'], true);
  }

  public function hasPermission($permission)
  {
    return in_array($permission, $this->getPermissions());
  }

  public function toArray()
  {
    return array_merge(parent::toArray(), array(
      'permissions' => $this->getPermissions(),
    ));
  }
}