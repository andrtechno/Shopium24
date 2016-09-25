CREATE TABLE IF NOT EXISTS `{prefix}seo_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{prefix}redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_from` varchar(255) DEFAULT NULL,
  `url_to` varchar(255) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}seo_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `keywords` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{prefix}seo_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_id` int(11) NOT NULL,
  `param` varchar(255) DEFAULT NULL,
  `obj` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB;

ALTER TABLE `{prefix}seo_main`
  ADD CONSTRAINT `{prefix}seo_main_ibfk_1` FOREIGN KEY (`url`) REFERENCES `{prefix}seo_url` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
