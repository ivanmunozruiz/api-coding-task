DROP DATABASE IF EXISTS `lotr`;
CREATE DATABASE `lotr` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `lotr`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `factions`;
CREATE TABLE `factions` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `faction_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `equipments`;
CREATE TABLE `equipments` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `made_by` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `birth_date` date NOT NULL,
  `kingdom` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `equipment_id` char(36) NOT NULL,
  `faction_id` char(36) NOT NULL,
  KEY `equipment_id` (`equipment_id`),
  KEY `faction_id` (`faction_id`),
  CONSTRAINT `characters_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`),
  CONSTRAINT `characters_ibfk_2` FOREIGN KEY (`faction_id`) REFERENCES `factions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (
  `id`,
  `email`
) VALUES (
  '4e2e4f82-46e3-4a60-a6db-938482874a3b',
  'ivan.sazo@gmail.com'
);

INSERT INTO `factions` (
  `id`,
  `faction_name`,
  `description`
) VALUES (
  '71e884d1-b0e1-4e2d-b782-4c6cb56babe0',
  'MORDOR',
  'Mordor es un país situado al sureste de la Tierra Media, que tuvo gran importancia durante la Guerra del Anillo por ser el lugar donde Sauron, el Señor Oscuro, decidió edificar su fortaleza de Barad-dûr para intentar atacar y dominar a todos los pueblos de la Tierra Media.'
);

INSERT INTO `equipments` (
  `id`,
  `name`,
  `type`,
  `made_by`
) VALUES (
  'fcad12c3-8714-4728-a18c-9f27e1db5177',
  'Maza de Sauron',
  'arma',
  'desconocido'
);

INSERT INTO `characters` (
  `id`,
  `name`,
  `birth_date`,
  `kingdom`,
  `equipment_id`,
  `faction_id`
) VALUES (
  'b1d3a1de-abc5-45bb-a3fb-883ebd68d20f',
  'SAURON',
  '3019-03-25',
  'AINUR',
  'fcad12c3-8714-4728-a18c-9f27e1db5177',
  '71e884d1-b0e1-4e2d-b782-4c6cb56babe0'
);
