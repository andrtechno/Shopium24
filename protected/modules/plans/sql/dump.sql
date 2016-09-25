
CREATE TABLE IF NOT EXISTS `{prefix}plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;


INSERT INTO `{prefix}plans` (`id`, `name`) VALUES
(1, 'Lite',299),
(2, 'Standard',599),
(3, 'Pro',699);


CREATE TABLE IF NOT EXISTS `{prefix}plans_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hint` text,
  `ordern` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}plans_options_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `ordern` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}plans_option_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_id` (`option_id`,`plan_id`)
) ENGINE=MyISAM;


