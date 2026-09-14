-- GARBALIA POS performance indexes
-- Safe to run more than once on MySQL/MariaDB via phpMyAdmin.
-- This index complements idx_orders_day_table_status:
--   * idx_orders_day_table_status is ideal for one specific table
--   * idx_orders_day_status_table is ideal for all open orders in one day

SET @garbalia_db = DATABASE();
SET @garbalia_idx_exists = (
  SELECT COUNT(*)
  FROM information_schema.statistics
  WHERE table_schema = @garbalia_db
    AND table_name = 'orders'
    AND index_name = 'idx_orders_day_status_table'
);

SET @garbalia_sql = IF(
  @garbalia_idx_exists = 0,
  'ALTER TABLE orders ADD INDEX idx_orders_day_status_table (business_day_id, status, table_id, id)',
  'SELECT 1'
);

PREPARE garbalia_stmt FROM @garbalia_sql;
EXECUTE garbalia_stmt;
DEALLOCATE PREPARE garbalia_stmt;
