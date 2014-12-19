-- to be run as game_admin on game database
-- psql game game_admin

DROP SCHEMA IF EXISTS event CASCADE;
DROP SCHEMA IF EXISTS public CASCADE;
DROP SCHEMA IF EXISTS ref CASCADE;

-- ENUM 'reference' tables
CREATE SCHEMA ref;
SET SCHEMA 'ref';

CREATE TYPE character_class AS ENUM
  ('Cleric', 'Fighter', 'Rogue', 'Wizard', 'Paladin', 'Shaman', 'Druid', 'Ranger');

CREATE TYPE permission AS ENUM ('Admin', 'Banker', 'Trainer');

-- core tables
CREATE SCHEMA public;
SET SCHEMA 'public';

-- a group of players
CREATE TABLE guild (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  name VARCHAR NOT NULL UNIQUE,
  username VARCHAR UNIQUE
);

-- a user who can log in to the system (may or may not be a player)
CREATE TABLE account (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  username VARCHAR UNIQUE,
  email VARCHAR UNIQUE,
  password_hash VARCHAR,
  api_token VARCHAR UNIQUE,
  permissions ref.permission[]
);

CREATE TABLE player (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  deleted_at TIMESTAMP,
  name VARCHAR NOT NULL UNIQUE,
  account_id INT NOT NULL REFERENCES account,
  guild_id INT NOT NULL REFERENCES guild,
  character_class ref.character_class,
  avatar VARCHAR
);

-- tables where items occur over time
-- may be subject to archiving
CREATE SCHEMA event;
SET SCHEMA 'event';

CREATE TABLE gold_transfer (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  from_player_id INT REFERENCES public.player,
  to_player_id INT REFERENCES public.player,

  -- amount must be positive, players will be placed in
  -- 'from' or 'to' depending on direction
  -- if the other player_id is null it indicates a transfer
  -- to or from the 'bank'
  amount INT NOT NULL CHECK (amount >= 0),
  description VARCHAR,

  -- must have at least a 'from' or 'to' player
  CHECK (from_player_id IS NOT NULL OR to_player_id IS NOT NULL),
  CHECK (from_player_id != to_player_id)
);

CREATE TABLE xp_accrual (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  player_id INT NOT NULL REFERENCES public.player,

  -- an amount of 0 indicates a missed opportunity to earn
  -- experience
  amount INT NOT NULL CHECK (amount >= 0),
  description VARCHAR
);
