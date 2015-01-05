CREATE OR REPLACE FUNCTION GET_LEVEL
(
  xp INT
)
RETURNS INT
LANGUAGE SQL
IMMUTABLE
AS $$
  SELECT FLOOR(POWER($1, 0.8) / 10)::INT + 1;
$$;