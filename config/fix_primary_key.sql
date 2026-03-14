-- Fix "Duplicate entry '0' for key 'PRIMARY'" error
-- Run this if you get that error during register or add item.
--
-- Option A - phpMyAdmin: Select marketplace_db, go to SQL tab, paste and run:
-- Option B - Command line: mysql -u root marketplace_db < config/fix_primary_key.sql

USE marketplace_db;

-- Ensure id columns have AUTO_INCREMENT (fixes tables that were created without it)
ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE items MODIFY id INT NOT NULL AUTO_INCREMENT;
