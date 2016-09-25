CREATE TABLE IF NOT EXISTS `{prefix}shop_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(10) unsigned DEFAULT NULL,
  `rgt` int(10) unsigned DEFAULT NULL,
  `level` smallint(5) unsigned DEFAULT NULL,
  `seo_alias` varchar(255) DEFAULT NULL,
  `full_path` varchar(255) DEFAULT '',
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `url` (`seo_alias`),
  KEY `full_path` (`full_path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `{prefix}shop_category` (`id`, `lft`, `rgt`, `level`, `seo_alias`, `full_path`, `description`, `image`, `switch`) VALUES
(1, 1, 2, 1, 'root', '', NULL, NULL, 1);


CREATE TABLE IF NOT EXISTS `{prefix}shop_category_translate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;



INSERT INTO `{prefix}shop_category_translate` (`id`, `object_id`, `language_id`, `name`, `seo_title`, `seo_keywords`, `seo_description`, `description`) VALUES
(1, 1, 1, 'root', NULL, NULL, NULL, NULL);



CREATE TABLE IF NOT EXISTS `{prefix}shop_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `iso` varchar(10) DEFAULT '',
  `symbol` varchar(10) DEFAULT '',
  `rate` float(10,3) DEFAULT NULL,
  `rate_old` float(10,3) DEFAULT NULL COMMENT 'старый курс',
  `main` tinyint(1) DEFAULT NULL,
  `default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `{prefix}shop_currency` (`id`, `name`, `iso`, `symbol`, `rate`, `rate_old`, `main`, `default`) VALUES
(1, 'Гривна', 'UAH', 'грн.', 1.000, NULL, 1, 1);


CREATE TABLE IF NOT EXISTS `{prefix}shop_payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(11) DEFAULT NULL,
  `switch` tinyint(1) DEFAULT NULL,
  `payment_system` varchar(100) DEFAULT '',
  `ordern` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `{prefix}shop_payment_method` (`id`, `currency_id`, `switch`, `payment_system`, `ordern`) VALUES
(1, 1, 1, 'privat24', 1),
(2, 1, 1, 'webmoney', 2);


CREATE TABLE IF NOT EXISTS `{prefix}shop_payment_method_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` varchar(2) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;


INSERT INTO `{prefix}shop_payment_method_translate` (`id`, `object_id`, `language_id`, `name`, `description`) VALUES
(1, 1, '1', 'Привет24', 'авыфаыв'),
(2, 1, '2', 'Привет24', 'авыфаыв'),
(3, 2, '1', 'WebMoney', ''),
(4, 2, '2', 'WebMoney', '');



CREATE TABLE IF NOT EXISTS `{prefix}shop_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(11) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `max_price` float(10,2) NOT NULL DEFAULT '0.00',
  `switch` tinyint(1) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `availability` tinyint(2) DEFAULT '1',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `ordern` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `price` (`price`),
  KEY `max_price` (`max_price`),
  KEY `is_active` (`switch`),
  KEY `created` (`date_create`),
  KEY `updated` (`date_update`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


INSERT INTO `{prefix}shop_product` (`id`, `currency_id`, `price`, `max_price`, `switch`, `quantity`, `availability`, `date_create`, `date_update`, `discount`, `ordern`) VALUES
(1, NULL, 1000.00, 0.00, 1, 1, 1, '2015-09-24 13:22:38', '2015-09-24 14:41:03', '', 1),
(2, NULL, 500.00, 0.00, 1, 1, 1, '2015-09-24 14:44:14', NULL, '', 2),
(3, NULL, 160.00, 0.00, 1, 1, 1, '2015-09-24 14:44:33', '2015-09-24 14:44:49', '', 3);


CREATE TABLE IF NOT EXISTS `{prefix}shop_product_category_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `{prefix}shop_product_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `short_description` text,
  `full_description` text,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;



INSERT INTO `{prefix}shop_product_translate` (`id`, `object_id`, `language_id`, `name`, `short_description`, `full_description`, `seo_title`, `seo_keywords`, `seo_description`) VALUES
(1, 3, 1, 'Тарифный план LITE', '', '', '', '', ''),
(2, 3, 2, 'asdfasdfasd', '', '', '', '', ''),
(3, 4, 1, 'asdfasdf', '', '', '', '', ''),
(4, 4, 2, 'asdfasdf', '', '', '', '', ''),
(5, 1, 1, 'Тарифный план PRO', '', NULL, '', '', ''),
(6, 1, 2, 'gdfgsdfg', '', NULL, '', '', ''),
(7, 2, 1, 'Тарифный план STANDART', '', NULL, '', '', ''),
(8, 2, 2, 'Тарифный план STANDART', '', NULL, '', '', ''),
(9, 3, 1, 'Тарифный план LITE', '', NULL, '', '', ''),
(10, 3, 2, 'Тарифный план LITE', '', NULL, '', '', '');
