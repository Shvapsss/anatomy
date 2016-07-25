-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tbl_answer`;
CREATE TABLE `tbl_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `right` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `tbl_answer_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `tbl_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tbl_chapter`;
CREATE TABLE `tbl_chapter` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `first_page` int(4) NOT NULL,
  `paid` int(1) NOT NULL,
  `file` varchar(255) NOT NULL,
  `upload_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_chapter` (`id`, `title`, `first_page`, `paid`, `file`, `upload_date`) VALUES
(6,	'«Концептуальные основы финансовой отчетности»',	1,	1,	'01.pdf',	1442347589),
(7,	'МСФО (IAS) 1 «Представление  финансовой отчётности»',	2,	1,	'02.pdf',	1442347610),
(8,	'МСФО (IAS) 8 «Учетные политики, изменения в бухгалтерских оценках и ошибки»',	3,	1,	'03.pdf',	1442347631),
(9,	'МСФО (IFRS) 1 «Применение Международных стандартов финансовой отчетности впервые»',	4,	1,	'04.pdf',	1442347652),
(10,	'МСФО (IAS) 16 «Основные средства»',	5,	1,	'05.pdf',	1442347671),
(11,	'МСФО (IAS) 17 «Аренда»',	6,	0,	'06.pdf',	1442347689),
(12,	'МСФО (IAS) 40 «Инвестиционное имущество»',	7,	1,	'07.pdf',	1442347712),
(13,	'МСФО (IAS) 38 «Нематериальные активы»',	8,	1,	'08.pdf',	1442347733),
(14,	'МСФО (IAS) 23 «Затраты по займам»',	9,	1,	'09.pdf',	1442347762),
(15,	'МСФО (IFRS) 6 «Разведка и оценка запасов минеральных ресурсов»',	10,	1,	'10.pdf',	1442347791),
(16,	'МСФО (IAS) 2 «Запасы»',	11,	1,	'11.pdf',	1442347823),
(17,	'МСФО (IAS) 41 «Сельское хозяйство»',	12,	1,	'12.pdf',	1442347857),
(18,	'МСФО (IAS) 20 «Учет субсидий и раскрытие информации о правительственной помощи»',	13,	1,	'13.pdf',	1442347878),
(19,	'МСФО (IAS) 36 «Обесценение активов»',	14,	1,	'14.pdf',	1442347925),
(22,	'МСФО (IFRS) 9 «Финансовые инструменты», МСФО (IAS) 32 «Финансовые инструменты: представление информации»,МСФО (IFRS) 7 «Финансовые инструменты: раскрытие информации»',	15,	0,	'15.pdf',	1442347984),
(23,	'МСФО (IAS) 37 «Резервы, условные  обязательства и условные активы»',	16,	1,	'16.pdf',	1442348015),
(24,	'МСФО (IAS) 19 «Вознаграждения работников»',	17,	1,	'17.pdf',	1442348041),
(25,	'МСФО (IFRS) 2 «Платежи с использованием акций»',	18,	1,	'18.pdf',	1442348067),
(26,	'МСФО (IAS) 12 «Налоги на прибыль»',	19,	1,	'19.pdf',	1442348171),
(27,	'МСФО (IFRS) 15 «Выручка по договорам с покупателями»',	20,	1,	'20.pdf',	1442348234),
(28,	'Консолидированная отчетность: область применения',	21,	1,	'21.pdf',	1442348252),
(29,	'МСФО (IFRS) 10 «Консолидированная  финансовая отчетность»',	22,	1,	'22.pdf',	1442348273),
(30,	'МСФО (IFRS) 3 «Объединения компаний»',	23,	1,	'23.pdf',	1442348305),
(31,	'Консолидация',	24,	1,	'24.pdf',	1442348320),
(32,	'МСФО 28 (IAS) «Инвестиции в ассоциированные компании и совместные предприятия»',	25,	1,	'25.pdf',	1442348336),
(33,	'МСФО (IFRS) 11 «Соглашения  о совместной деятельности»',	26,	1,	'26.pdf',	1442348359),
(34,	'МСФО (IFRS) 12 «Раскрытие информации о долях участия в других компаниях»',	27,	1,	'27.pdf',	1442348372),
(35,	'МСФО (IAS) 33 «Прибыль на акцию»',	28,	1,	'28.pdf',	1442348383),
(36,	'МСФО (IAS) 34 «Промежуточная финансовая отчетность»',	29,	1,	'29.pdf',	1442348396),
(37,	'МСФО (IAS) 21 «Влияние изменений  валютных курсов»',	30,	1,	'30.pdf',	1442348415),
(38,	'МСФО (IFRS) 8 «Операционные сегменты»',	31,	1,	'31.pdf',	1442348432),
(39,	'МСФО (IFRS) 5 «Долгосрочные активы, предназначенные для продажи, и прекращенная деятельность» ',	32,	0,	'32.pdf',	1442348447),
(40,	'МСФО (IAS) 24 «Раскрытие информации  о связанных сторонах»',	33,	1,	'33.pdf',	1442348460),
(41,	'МСФО (IFRS) 13 «Оценка справедливой стоимости»',	34,	1,	'34.pdf',	1442348474),
(42,	'МСФО (IAS) 10 «События после отчетной даты»',	35,	1,	'35.pdf',	1443554979),
(43,	'МСФО для малых и средних предприятий',	36,	1,	'36.pdf',	1442348501);

DROP TABLE IF EXISTS `tbl_question`;
CREATE TABLE `tbl_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `explanation` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  CONSTRAINT `tbl_question_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tbl_test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tbl_test`;
CREATE TABLE `tbl_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `active` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id` int(11) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `firstname` varchar(128) DEFAULT NULL,
  `lastname` varchar(128) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  `registred` int(11) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) DEFAULT '0' COMMENT 'Displaying confirmed user own email or not'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user` (`id`, `email`, `firstname`, `lastname`, `pass`, `role`, `registred`, `confirmed`) VALUES
(1,	'admin@admin.com',	'Vexadev2014',	NULL,	'$2a$13$pM.ncJIYIQ213HsA.H1Jq.gU/ps6Bv7wi5MMW9JATCZrHuJvJpR6u',	'admin',	0,	0);

-- 2015-10-03 16:13:52
