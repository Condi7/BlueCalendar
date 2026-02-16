-- BlueCalendar Schema upgrade to 1.0.1
-- Adds support for a second organization supervisor

SET @col_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'organization'
      AND COLUMN_NAME = 'supervisor2'
);

SET @sqlstmt = IF(
    @col_exists = 0,
    'ALTER TABLE `organization` ADD `supervisor2` INT NULL DEFAULT NULL COMMENT ''Second user receiving a copy of accepted and rejected leave requests''',
    'SELECT "organization.supervisor2 already exists"'
);

PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
