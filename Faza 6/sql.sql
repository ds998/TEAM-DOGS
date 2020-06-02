/*
Created: 14.4.2020.
Modified: 2.6.2020.
Model: MySQL 8.0
Database: MySQL 8.0
*/

-- Create tables section -------------------------------------------------

-- Table User

CREATE TABLE `User`
(
  `idUser` Int NOT NULL,
  `username` Varchar(40) NOT NULL,
  `email` Varchar(40) NOT NULL,
  `passwordHash` Varchar(60) NOT NULL,
  `isGuest` Int
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
  `idUser` Int NOT NULL,
  `maxPlayers` Int NOT NULL DEFAULT 10,
  `lobbyName` Varchar(15) NOT NULL,
  `password` Varchar(10),
  `PlayerList` Varchar(120),
  `status` Varchar(3),
  `inGame` Int
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

ALTER TABLE `Lobby` ADD UNIQUE `idUser` (`idUser`)
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
  `numberOfRatings` Int,
  `descr` Varchar(280) NOT NULL,
  `suits` Varchar(4),
  `name` Varchar(30) NOT NULL
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

-- Table Hdecks

CREATE TABLE `Hdecks`
(
  `idDeck` Int NOT NULL,
  `orderNum` Int NOT NULL
)
;

ALTER TABLE `Hdecks` ADD PRIMARY KEY (`idDeck`)
;

-- Table Chat

CREATE TABLE `Chat`
(
  `idLobby` Int NOT NULL,
  `chat` Varchar(800)
)
;

ALTER TABLE `Chat` ADD PRIMARY KEY (`idLobby`)
;

-- Table User_Hand

CREATE TABLE `User_Hand`
(
  `idLobby` Int NOT NULL,
  `idUser` Int NOT NULL,
  `cards` Varchar(200)
)
;

ALTER TABLE `User_Hand` ADD PRIMARY KEY (`idLobby`, `idUser`)
;

-- Table Lobby_Deck

CREATE TABLE `Lobby_Deck`
(
  `idLobby` Int NOT NULL,
  `cards` Varchar(1280)
)
;

ALTER TABLE `Lobby_Deck` ADD PRIMARY KEY (`idLobby`)
;

-- Table Game_Update

CREATE TABLE `Game_Update`
(
  `idLobby` Int NOT NULL,
  `idUser` Int NOT NULL,
  `updateF` Varchar(420)
)
;

ALTER TABLE `Game_Update` ADD PRIMARY KEY (`idUser`, `idLobby`)
;


