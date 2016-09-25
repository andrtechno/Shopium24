CREATE TABLE IF NOT EXISTS `{prefix}order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `secret_key` varchar(10) DEFAULT '',
  `total_price` float(10,2) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `secret_key` (`secret_key`),
  KEY `status_id` (`status_id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `name` text,
  `quantity` smallint(6) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{prefix}order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `color` varchar(6) NOT NULL,
  `ordern` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`ordern`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;



INSERT INTO `{prefix}order_status` (`id`, `name`, `color`, `ordern`) VALUES
(1, 'Новый', 'b3fcb3', 1),
(2, 'В обработке', 'd9e68f', 2),
(3, 'Отправлен', 'f79191', 3),
(4, 'Расчет', '', 4);

