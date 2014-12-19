<?php

namespace Model;

class Retriever
{
  public function get($modelClass)
  {
    $model = new $modelClass;

    return $model->newQuery();
  }
}