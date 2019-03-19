CREATE 
  FUNCTION `uuid_to_ouuid`(uuid BINARY(36))
  RETURNS binary(16) DETERMINISTIC
  RETURN UNHEX(CONCAT(
  SUBSTR(uuid, 15, 4),
  SUBSTR(uuid, 10, 4),
  SUBSTR(uuid, 1, 8),
  SUBSTR(uuid, 20, 4),
  SUBSTR(uuid, 25, 12)
));

CREATE 
  FUNCTION ouuid_to_uuid(uuid BINARY(16))
  RETURNS VARCHAR(36)
  RETURN LOWER(CONCAT(
  SUBSTR(HEX(uuid), 9, 8), '-',
  SUBSTR(HEX(uuid), 5, 4), '-',
  SUBSTR(HEX(uuid), 1, 4), '-',
  SUBSTR(HEX(uuid), 17,4), '-',
  SUBSTR(HEX(uuid), 21, 12 )
));