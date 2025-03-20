CREATE OR REPLACE FUNCTION uuid_to_ouuid(uuid BYTEA)
RETURNS BYTEA AS $$
DECLARE
  hex_uuid TEXT;
BEGIN
  hex_uuid := encode(uuid, 'hex');
  RETURN decode(
    substring(hex_uuid FROM 9 FOR 4) ||
    substring(hex_uuid FROM 5 FOR 4) ||
    substring(hex_uuid FROM 1 FOR 8) ||
    substring(hex_uuid FROM 17 FOR 4) ||
    substring(hex_uuid FROM 21 FOR 12),
    'hex');
END;
$$ LANGUAGE plpgsql IMMUTABLE;

CREATE OR REPLACE FUNCTION ouuid_to_uuid(uuid BYTEA)
RETURNS VARCHAR(36) AS $$
DECLARE
  hex_uuid TEXT;
BEGIN
  hex_uuid := encode(uuid, 'hex');
  RETURN lower(
    substring(hex_uuid FROM 9 FOR 8) || '-' ||
    substring(hex_uuid FROM 5 FOR 4) || '-' ||
    substring(hex_uuid FROM 1 FOR 4) || '-' ||
    substring(hex_uuid FROM 17 FOR 4) || '-' ||
    substring(hex_uuid FROM 21 FOR 12)
  );
END;
$$ LANGUAGE plpgsql IMMUTABLE;
