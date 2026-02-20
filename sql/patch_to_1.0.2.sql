-- BlueCalendar Schema upgrade to 1.0.2
-- Increase precision of entitleddays.days to avoid overtime hour conversion drift (e.g. 1h -> 1.04h)

ALTER TABLE `entitleddays`
MODIFY `days` decimal(10,4) NOT NULL COMMENT 'Number of days (can be negative so as to deduct/adjust entitlement)';

-- Recompute existing overtime-linked entitlements with contract daily hours precision
UPDATE `entitleddays` ed
INNER JOIN `overtime` ot ON ot.`id` = ed.`overtime`
INNER JOIN `users` u ON u.`id` = ot.`employee`
LEFT JOIN `contracts` c ON c.`id` = u.`contract`
SET ed.`days` = ROUND(
	ot.`duration` / (IFNULL(NULLIF(c.`daily_duration`, 0), 480) / 60),
	4
)
WHERE ed.`overtime` IS NOT NULL;
