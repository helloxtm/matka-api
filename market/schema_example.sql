-- Example tables for market module (edit names/columns to match your site)

CREATE TABLE IF NOT EXISTS `market_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `open_time` varchar(32) DEFAULT NULL,
  `close_time` varchar(32) DEFAULT NULL,
  `sat_day` char(1) DEFAULT '0',
  `sun_day` char(1) DEFAULT '0',
  `bg_yellow` char(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `market_results` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `result` varchar(64) NOT NULL,
  `result_date` date NOT NULL,
  `open_time` varchar(32) DEFAULT NULL,
  `close_time` varchar(32) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_date` (`name`, `result_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
