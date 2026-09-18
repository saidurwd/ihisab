-- Performance optimization indexes for transaction queries
-- Execute these SQL statements to add composite indexes

-- Index for user-scoped date range queries
ALTER TABLE `os_transaction`
  ADD INDEX `idx_user_created` (`user`, `created`);

-- Index for user-scoped transaction type + date queries
ALTER TABLE `os_transaction`
  ADD INDEX `idx_user_type_created` (`user`, `transaction_type`, `created`);

-- Index for user-scoped account lookups
ALTER TABLE `os_transaction`
  ADD INDEX `idx_user_account` (`user`, `account`);

-- Index for user-scoped status filtering
ALTER TABLE `os_transaction`
  ADD INDEX `idx_user_status` (`user`, `status`);

-- Index for transaction_tag lookups by transaction
ALTER TABLE `os_transaction_tag`
  ADD INDEX `idx_transaction_tag` (`transaction`, `tag`);

-- Index for transaction_tag lookups by tag
ALTER TABLE `os_transaction_tag`
  ADD INDEX `idx_tag_transaction` (`tag`, `transaction`);

-- Index for account user lookups
ALTER TABLE `os_account`
  ADD INDEX `idx_user_status` (`user`, `status`);
