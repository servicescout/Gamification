<?php

$container = new \Illuminate\Container\Container();
$conFactory = new Illuminate\Database\Connectors\ConnectionFactory($container);
$con = $conFactory->make(\Util\Config::get()->getValue('database'));

$resolver = new Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $con);
$resolver->setDefaultConnection('default');

\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);