CREATE TABLE IF NOT EXISTS `satta_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `result_time` varchar(32) DEFAULT NULL,
  `bg_yellow` char(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `satta_results` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `result` varchar(32) NOT NULL,
  `result_date` date NOT NULL,
  `result_time` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_date` (`name`, `result_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
