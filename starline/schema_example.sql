CREATE TABLE IF NOT EXISTS `starline_games` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `starline_results` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `game` varchar(120) NOT NULL,
  `slot` int NOT NULL DEFAULT 0,
  `result_time` varchar(32) DEFAULT NULL,
  `patti` varchar(16) DEFAULT NULL,
  `sd` varchar(8) DEFAULT NULL,
  `result` varchar(32) DEFAULT NULL,
  `result_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_slot_date` (`game`, `slot`, `result_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
