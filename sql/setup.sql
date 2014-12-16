-- to be run as postgres user
-- replace password and add to config.local.php

CREATE USER game_admin WITH PASSWORD 'XYZ';
CREATE DATABASE game OWNER game_admin;

\c game

DROP SCHEMA public;