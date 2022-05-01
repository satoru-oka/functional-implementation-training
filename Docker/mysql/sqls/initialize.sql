CREATE DATABASE qa_app;
use qa_app;

CREATE TABLE `answers` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `question_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `body` varchar(256) NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `questions` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `question_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `body` varchar(256) NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `question_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `body` varchar(256) NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
