# TakeLessons Gamification

## Technologies Used
- Slim
- Angular
- PostgreSQL
- Composer
- Eloquent ORM

## Getting Started
> You will need [Composer](https://getcomposer.org/) installed locally to complete setup

Clone the repository to `/var/www/game`

```
cd /var/www
git clone git@github.com:servicescout/Gamification.git game
```

Copy the virtual host config file and change the server name if needed
> Instructions are for CentOS
```
cd /var/www/game

sudo cp game.conf /etc/httpd/conf.d/
sudo vim /etc/httpd/conf.d/game.conf
sudo /etc/init.d/httpd restart
```

Install the proper vendor code versions using Composer
> This snippet assumes `composer` is in your path

```
cd /var/www/game
composer install
```

Set up the database (use sql/setup.sql and sql/init.sql for assistance)

Add a `config.local.php (/var/www/game/config/config.local.php)`
```
<?php

$config['database'] = array(
  'driver' => 'pgsql',
  'host' => '127.0.0.1',
  'database' => 'game',
  'username' => 'game_admin',
  'password' => 'YOUR_CHOICE',
  'charset' => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix' => '',
  'schema' => 'public',
);

```

Complete.
