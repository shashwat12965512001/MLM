-- Users
CREATE TABLE IF NOT EXISTS `u338280037_project`.`users` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `email` VARCHAR(50) NOT NULL , `phone` bigint(11) NOT NULL , `role` VARCHAR(50) NOT NULL, `sponser_id` INT(50) NOT NULL , `balance` INT(50) NOT NULL, PRIMARY KEY (`id`));

-- Transactions
CREATE TABLE IF NOT EXISTS `u338280037_project`.`transactions` (`id` INT NOT NULL AUTO_INCREMENT , `date` DATE NOT NULL , `type` VARCHAR(50) NOT NULL , `description` VARCHAR(255) NOT NULL , `amount` INT(50) NOT NULL, `user_id` INT(50) NOT NULL, PRIMARY KEY (`id`));

-- Withdraw Requests
CREATE TABLE IF NOT EXISTS `u338280037_project`.`withdraw_requests` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT(50) NOT NULL , `amount` INT(50) NOT NULL , `status` VARCHAR(50) NOT NULL, `date_time` DATETIME NOT NULL , PRIMARY KEY (`id`));

-- Options
CREATE TABLE IF NOT EXISTS `u338280037_project`.`options` (`name` VARCHAR(50) NOT NULL , `value` VARCHAR(255) NOT NULL , UNIQUE `option_name` (`name`(50)));

Truncate table `users`;
Truncate table `transactions`;
Truncate table `withdraw_requests`;
Truncate table `options`;