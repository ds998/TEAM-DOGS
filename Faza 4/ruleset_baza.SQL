/*
Created: 14.4.2020.
Modified: 14.4.2020.
Model: MySQL 8.0
Database: MySQL 8.0
*/

-- Create tables section -------------------------------------------------

-- Table User

CREATE TABLE `User`
(
  `idUser` Int NOT NULL,
  `username` Varchar(15) NOT NULL,
  `email` Varchar(25) NOT NULL,
  `passwordHash` Varchar(45) NOT NULL,
  `salt` Varchar(10) NOT NULL
)
;

ALTER TABLE `User` ADD PRIMARY KEY (`idUser`)
;

ALTER TABLE `User` ADD UNIQUE `username` (`username`)
;

ALTER TABLE `User` ADD UNIQUE `Attribute1` (`email`)
;

ALTER TABLE `User` ADD UNIQUE `idUser` (`idUser`)
;

-- Table Lobby

CREATE TABLE `Lobby`
(
  `idLobby` Int NOT NULL,
  `idDeck` Int NOT NULL,
  `idSession` Varchar(15) NOT NULL,
  `maxPlayers` Int NOT NULL DEFAULT 10,
  `lobbyName` Varchar(15) NOT NULL,
  `password` Varchar(10)
)
;

CREATE INDEX `IX_Relationship6` ON `Lobby` (`idDeck`)
;

ALTER TABLE `Lobby` ADD PRIMARY KEY (`idLobby`)
;

ALTER TABLE `Lobby` ADD UNIQUE `idLobby` (`idLobby`)
;

ALTER TABLE `Lobby` ADD UNIQUE `idDeck` (`idDeck`)
;

ALTER TABLE `Lobby` ADD UNIQUE `idSession` (`idSession`)
;

-- Table User_Decks

CREATE TABLE `User_Decks`
(
  `idDeck` Int NOT NULL,
  `idUser` Int NOT NULL,
  `idCreator` Int NOT NULL,
  `Rating` Int NOT NULL
)
;

ALTER TABLE `User_Decks` ADD PRIMARY KEY (`idDeck`, `idUser`)
;

-- Table Deck

CREATE TABLE `Deck`
(
  `idDeck` Int NOT NULL,
  `idUser` Int NOT NULL,
  `cardRules` Varchar(210) NOT NULL,
  `Cards` Varchar(120) NOT NULL,
  `globalRules` Varchar(20) NOT NULL,
  `Rating` Double,
  `numberOfPlays` Int,
  `numberOfRatings` Int
)
;

CREATE INDEX `IX_Relationship5` ON `Deck` (`idUser`)
;

ALTER TABLE `Deck` ADD PRIMARY KEY (`idDeck`)
;

ALTER TABLE `Deck` ADD UNIQUE `idDeck` (`idDeck`)
;

ALTER TABLE `Deck` ADD UNIQUE `idUser` (`idUser`)
;

-- Table Admins

CREATE TABLE `Admins`
(
  `idUser` Int NOT NULL
)
;

ALTER TABLE `Admins` ADD PRIMARY KEY (`idUser`)
;

-- Create foreign keys (relationships) section -------------------------------------------------

ALTER TABLE `User_Decks` ADD CONSTRAINT `User to Deck` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `User_Decks` ADD CONSTRAINT `I` FOREIGN KEY (`idDeck`) REFERENCES `Deck` (`idDeck`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `Deck` ADD CONSTRAINT `Relationship5` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `Lobby` ADD CONSTRAINT `Relationship6` FOREIGN KEY (`idDeck`) REFERENCES `Deck` (`idDeck`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `Admins` ADD CONSTRAINT `Relationship7` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;


