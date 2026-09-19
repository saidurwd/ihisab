-- Daily transaction summary email system
-- Run these SQL statements to create required tables

-- Email preferences table
CREATE TABLE IF NOT EXISTS `os_email_preference` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1,
  `frequency` ENUM('daily', 'weekly', 'monthly', 'never') DEFAULT 'daily',
  `last_sent_at` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_enabled` (`enabled`),
  INDEX `idx_last_sent` (`last_sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Email log table
CREATE TABLE IF NOT EXISTS `os_email_log` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `status` ENUM('sent', 'failed', 'skipped') DEFAULT 'sent',
  `error_message` TEXT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
