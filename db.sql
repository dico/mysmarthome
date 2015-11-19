-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Vert: localhost
-- Generert den: 22. Mai, 2015 01:24 AM
-- Tjenerversjon: 5.1.61
-- PHP-Versjon: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `msh_app`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_alerts`
--

CREATE TABLE IF NOT EXISTS `msh_alerts` (
  `alert_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_int_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `level` enum('low','medium','high') NOT NULL,
  `message` varchar(256) NOT NULL,
  `confirmed` tinyint(1) NOT NULL COMMENT '0=not needed, 1=need confirmation, 2=confirmed',
  PRIMARY KEY (`alert_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_cars`
--

CREATE TABLE IF NOT EXISTS `msh_cars` (
  `car_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `car_brand` varchar(64) NOT NULL,
  `car_model` varchar(64) NOT NULL,
  `car_licenseplate` varchar(32) NOT NULL,
  PRIMARY KEY (`car_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_categories`
--

CREATE TABLE IF NOT EXISTS `msh_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dataark for tabell `msh_categories`
--

INSERT INTO `msh_categories` (`cat_id`, `name`) VALUES
(1, 'Lights'),
(2, 'Temperature'),
(3, 'Humidity'),
(4, 'Wind'),
(5, 'Rain'),
(6, 'Doors'),
(7, 'Garage doors'),
(8, 'Windows'),
(9, 'Energy'),
(10, 'Network'),
(11, 'Weather'),
(12, 'Camera'),
(13, 'Movement'),
(14, 'Remote');


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_config`
--

CREATE TABLE IF NOT EXISTS `msh_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(256) NOT NULL,
  `config_value` varchar(256) NOT NULL,
  `comment` varchar(256) NOT NULL,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dataark for tabell `msh_config`
--

INSERT INTO `msh_config` (`config_id`, `config_name`, `config_value`, `comment`) VALUES
(1, 'page_title', 'MSH', 'Default page title if user title not set'),
(2, 'url', 'http://localhost/msh/', 'Full domain path. End with slash'),
(3, 'absolute_path', '/wwwroot/www/msh/', 'Full serverpath. End with slash.'),
(4, 'language_default', 'en_GB', ''),
(5, 'theme_desktop', 'msh2015', ''),
(6, 'footer_show_credits', '1', ''),
(7, 'footer_show_interface_change', '1', ''),
(8, 'footer_show_container', '1', ''),
(9, 'email_address_sender', 'noreply@my.mail.domain.no', ''),
(10, 'email_address_reply', 'noreply@my.mail.domain.no', ''),
(11, 'tmp_sync_limit', '1420774007', ''),
(12, 'time_outdated_value', '60', 'In minutes');


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_cronjobs`
--

CREATE TABLE IF NOT EXISTS `msh_cronjobs` (
  `cron_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `filepath` varchar(256) NOT NULL,
  `interval` int(11) NOT NULL COMMENT 'interval in sec',
  `last_run` int(11) NOT NULL,
  `disabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`cron_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Dataark for tabell `msh_cronjobs`
--

INSERT INTO `msh_cronjobs` (`cron_id`, `user_id`, `title`, `filepath`, `interval`, `last_run`, `disabled`) VALUES
(1, 1, 'Update current values', 'modules/settings/cron/update_values.php', 900, 1445820301, 0),
(2, 1, 'Events', 'modules/settings/cron/events.php', 15, 1445820901, 0),
(3, 1, 'Reduces log values older than one year by one each hour', 'modules/settings/cron/reduce_values_by_hour.php', 604800, 0, 0);



-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_devices`
--

CREATE TABLE IF NOT EXISTS `msh_devices` (
  `device_int_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `binding` varchar(64) NOT NULL,
  `device_ext_id` varchar(256) NOT NULL,
  `device_name` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `url` varchar(256) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `battery` double NOT NULL,
  `state` float NOT NULL COMMENT 'Device current state. Use when not log.',
  `dashboard` tinyint(4) NOT NULL COMMENT 'view on dashboard',
  `dashboard_size` varchar(32) NOT NULL COMMENT 'small, wide, big',
  `monitor` tinyint(4) NOT NULL,
  `public` tinyint(4) NOT NULL,
  `deactive` tinyint(4) NOT NULL,
  PRIMARY KEY (`device_int_id`),
  KEY `modul_id` (`module`,`device_ext_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_devices_has_category`
--

CREATE TABLE IF NOT EXISTS `msh_devices_has_category` (
  `device_int_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`device_int_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_devices_log`
--

CREATE TABLE IF NOT EXISTS `msh_devices_log` (
  `device_int_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `unit_id` smallint(6) NOT NULL,
  `value` float NOT NULL,
  PRIMARY KEY (`device_int_id`,`time`,`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_devices_methods`
--

CREATE TABLE IF NOT EXISTS `msh_devices_methods` (
  `device_int_id` varchar(32) NOT NULL,
  `method` varchar(32) NOT NULL COMMENT 'value=print value',
  `value` tinyint(1) NOT NULL COMMENT '1=value supported',
  PRIMARY KEY (`device_int_id`,`method`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Supported methods for events';

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_events`
--

CREATE TABLE IF NOT EXISTS `msh_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interval` smallint(6) NOT NULL,
  `last_run` datetime NOT NULL,
  `days` mediumint(9) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `msg_title` varchar(256) NOT NULL,
  `msg_description` varchar(256) NOT NULL,
  `alert_inapp` tinyint(1) NOT NULL,
  `alert_level` enum('low','medium','high') NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_events_actions`
--

CREATE TABLE IF NOT EXISTS `msh_events_actions` (
  `event_action_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `device_int_id` int(11) NOT NULL,
  `cmd` varchar(32) NOT NULL,
  PRIMARY KEY (`event_action_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_events_notify`
--

CREATE TABLE IF NOT EXISTS `msh_events_notify` (
  `notify_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `mail` varchar(256) NOT NULL COMMENT 'Mailaddress',
  `sms` varchar(32) NOT NULL COMMENT 'Mobilenumber',
  `alert_inapp` tinyint(1) NOT NULL,
  `alert_level` enum('low','medium','high') NOT NULL,
  PRIMARY KEY (`notify_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_events_triggers`
--

CREATE TABLE IF NOT EXISTS `msh_events_triggers` (
  `event_trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `rang` tinyint(4) NOT NULL,
  `trigger_operator` enum('AND','OR','') NOT NULL,
  `device_int_id` int(11) NOT NULL,
  `value_operator` enum('less','equal','high') NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`event_trigger_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_garage`
--

CREATE TABLE IF NOT EXISTS `msh_garage` (
  `garage_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `motor_int_id` int(11) NOT NULL,
  `status_int_id` int(11) NOT NULL,
  `status_value_open` int(11) NOT NULL,
  `status_value_closed` int(11) NOT NULL,
  `img_door_open` varchar(128) NOT NULL,
  `img_door_closed` varchar(128) NOT NULL,
  `webcam_device_int_id` int(11) NOT NULL,
  PRIMARY KEY (`garage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_ip_ban`
--

CREATE TABLE IF NOT EXISTS `msh_ip_ban` (
  `ip_address` varchar(64) NOT NULL,
  PRIMARY KEY (`ip_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_languages`
--

CREATE TABLE IF NOT EXISTS `msh_languages` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  `language_name` varchar(128) NOT NULL,
  `icon_flag` varchar(128) NOT NULL,
  `default` tinyint(4) NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `msh_languages` (`lang_id`, `code`, `language_name`, `icon_flag`, `default`) VALUES
(1, 'nb_NO', 'Norwegian', 'nor.png', 0),
(2, 'en_GB', 'English', 'gb.png', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_location`
--

CREATE TABLE IF NOT EXISTS `msh_location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `geo_lat` double NOT NULL,
  `geo_lon` double NOT NULL,
  `weather_sensor` int(11) NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_location_rooms`
--

CREATE TABLE IF NOT EXISTS `msh_location_rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `room_name` varchar(256) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_location_zones`
--

CREATE TABLE IF NOT EXISTS `msh_location_zones` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `zone_name` varchar(128) NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_modules`
--

CREATE TABLE IF NOT EXISTS `msh_modules` (
  `module_id` varchar(64) NOT NULL,
  `module_name` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_sms_providers`
--

CREATE TABLE IF NOT EXISTS `msh_sms_providers` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `url_auth` varchar(256) NOT NULL,
  `url` varchar(256) NOT NULL,
  `from_number` varchar(16) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `api_code` varchar(64) NOT NULL,
  `primary` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_units`
--

CREATE TABLE IF NOT EXISTS `msh_units` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_title` varchar(64) NOT NULL,
  `unit_short_tag` varchar(16) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=devices, 2=sensors, 3=both',
  `icon` varchar(64) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;


INSERT INTO `msh_units` (`unit_id`, `unit_title`, `unit_short_tag`, `type`, `icon`) VALUES
(1, 'Temperature', '°C', 2, '<i class="wi wi-thermometer"></i>'),
(2, 'Humidity', '%', 2, '<i class="wi wi-sprinkles"></i>'),
(3, 'Powerconsumption', 'kWh', 2, '<i class="wi wi-lightning"></i>'),
(4, 'On/Off', '', 1, '<i class="fa fa-power-off"></i>'),
(5, 'Dimmer', '%', 1, '<i class="fa fa-lightbulb-o"></i>'),
(6, 'Wind', 'm/s', 2, '<i class="wi wi-strong-wind"></i>'),
(7, 'Rain', 'mm', 2, '<i class="wi wi-rain"></i>'),
(8, 'Desibel', 'dB', 2, '<i class="fa fa-volume-up"></i>'),
(9, 'CO2', 'ppm', 2, '<i class="fa fa-building"></i>'),
(10, 'Pascal', 'Pa', 2, '<i class="fa fa-bars"></i>'),
(11, 'Bar', 'bar', 2, '<i class="fa fa-bars"></i>'),
(12, 'Standard atmosphere', 'atm', 2, '<i class="fa fa-globe"></i>'),
(13, 'Pounds per square inch', 'psi', 2, '<i class="fa fa-bars"></i>'),
(14, 'Battery', '%', 2, '<i class="fa fa-plug"></i>'),
(15, 'Push', '', 1, '<i class="fa fa-power-off"></i>');


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_url_triggers`
--

CREATE TABLE IF NOT EXISTS `msh_url_triggers` (
  `url_id` varchar(64) NOT NULL,
  `device_int_id` int(11) NOT NULL,
  `set_value` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(256) NOT NULL,
  PRIMARY KEY (`url_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_users`
--

CREATE TABLE IF NOT EXISTS `msh_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) NOT NULL,
  `displayname` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `home_status` enum('home','away','night') NOT NULL,
  `page_title` varchar(64) NOT NULL,
  `public_name` varchar(64) NOT NULL,
  `language` varchar(16) NOT NULL,
  `apikey` varchar(256) NOT NULL,
  `role` enum('public','user','admin') NOT NULL,
  `public_allow` tinyint(4) NOT NULL,
  `theme` varchar(128) NOT NULL,
  `page_refresh_time` int(11) NOT NULL COMMENT 'minutes',
  `deactive` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `public_name` (`public_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


--
-- Dataark for tabell `msh_users`
-- DEFAULT PW: "kake123"
--

INSERT INTO `msh_users` (`user_id`, `mail`, `displayname`, `password`, `mobile`, `home_status`, `page_title`, `public_name`, `language`, `role`, `public_allow`, `theme`, `page_refresh_time`, `deactive`) VALUES
(1, 'admin', 'admin', '5882aa6aef8158fd41a90bb6080894eee30d80f07adf1de29b254dd7b480fdce', '', 'home', 'MSH', 'MSH', 'en_GB', 'admin', 0, 'msh2015', 15, 0);


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_users_has_module`
--

CREATE TABLE IF NOT EXISTS `msh_users_has_module` (
  `user_id` int(11) NOT NULL,
  `module_id` varchar(64) NOT NULL,
  `rang` smallint(6) NOT NULL,
  PRIMARY KEY (`user_id`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_users_login_log`
--

CREATE TABLE IF NOT EXISTS `msh_users_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) NOT NULL,
  `time_created` datetime NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `browser` varchar(128) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0=loginform, 1=resetcode',
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_users_login_pw_reset`
--

CREATE TABLE IF NOT EXISTS `msh_users_login_pw_reset` (
  `user_id` int(11) NOT NULL,
  `mail` varchar(256) NOT NULL,
  `token` varchar(128) NOT NULL,
  `code` int(11) NOT NULL,
  `time_created` datetime NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `attempt_count` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `msh_users_login_remember`
--

CREATE TABLE IF NOT EXISTS `msh_users_login_remember` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `valid_to` datetime NOT NULL,
  `browser` varchar(256) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------