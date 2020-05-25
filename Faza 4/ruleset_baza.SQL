

-- Create tables section
-- Table User

CREATE TABLE `User`
(
  `idUser` Int NOT NULL AUTO_INCREMENT,
  `username` Varchar(15) NOT NULL,
  `email` Varchar(25) NOT NULL,
  `passwordHash` Varchar(45) NOT NULL,
  `salt` Varchar(10) NOT NULL,
  `isGuest` int,
   PRIMARY KEY(`idUser`)
)
;

ALTER TABLE `User` ADD UNIQUE `username` (`username`)
;

ALTER TABLE `User` ADD UNIQUE `email` (`email`)
;

ALTER TABLE `User` ADD UNIQUE `idUser` (`idUser`)
;

-- Table Lobby

CREATE TABLE `Lobby`
(
  `idLobby` Int NOT NULL AUTO_INCREMENT,
  `idDeck` Int NOT NULL,
  `idUser` Int NOT NULL,
  `maxPlayers` Int NOT NULL DEFAULT 10,
  `PlayerList` VARCHAR(120),
  `lobbyName` Varchar(15) NOT NULL,
  `password` Varchar(10),
   PRIMARY KEY(`idLobby`)
)
;

-- Table Deck

CREATE TABLE `Deck`
(
  `idDeck` Int NOT NULL AUTO_INCREMENT,
  `idUser` Int NOT NULL,
  `deckName` Varchar(30) not null,
  `deckDescription` Varchar(140),
  `cardRules` Varchar(210) NOT NULL,
  `Cards` Varchar(120) NOT NULL,
  `globalRules` Varchar(20) NOT NULL,
  `Rating` Double,
  `numberOfPlays` Int,
  `numberOfRatings` Int,
   PRIMARY KEY(`idDeck`)
)
;

CREATE INDEX `IX_Relationship5` ON `Deck` (`idUser`)
;

ALTER TABLE `Deck` ADD UNIQUE `idDeck` (`idDeck`)
;
create table `GameMove`(
	`idLobby` int not null,
	`cardsThrown` varchar(100) not null,
	`lastUserId` int not null,
	`moveTime` int not null,
);

ALTER TABLE `GameMove` ADD CONSTRAINT `Relationship10` FOREIGN KEY (`idLobby`) REFERENCES `Lobby` (`idLobby``) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `GameMove` ADD CONSTRAINT `Relationship12` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser``) ON DELETE NO ACTION ON UPDATE NO ACTION
;

create table `ChatMsg`(
	`idLobby` int not null,
	`username` varchar(30) not null,
	`msg` varchar(50) not null,
	`sequenceNum` int,
);

ALTER TABLE `ChatMsg` ADD CONSTRAINT `Relationship11` FOREIGN KEY (`idLobby`) REFERENCES `Lobby` (`idLobby`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

CREATE TABLE `HDecks`(
    `idDeck` int not null,
	`orderNum` int not null
);

ALTER TABLE `HDecks` ADD CONSTRAINT `Relationship5` FOREIGN KEY (`idDeck`) REFERENCES `Deck` (`idDeck`) ON DELETE NO ACTION ON UPDATE NO ACTION
;
ALTER TABLE `Deck` ADD CONSTRAINT `Relationship5698` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;
ALTER TABLE `Lobby` ADD CONSTRAINT `Relationship420` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

CREATE INDEX `IX_Relationship6` ON `Lobby` (`idDeck`)
;

ALTER TABLE `Lobby` ADD UNIQUE `idLobby` (`idLobby`)
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

ALTER TABLE `Deck` ADD CONSTRAINT `Relationship5789` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `Lobby` ADD CONSTRAINT `Relationship6hil` FOREIGN KEY (`idDeck`) REFERENCES `Deck` (`idDeck`) ON DELETE NO ACTION ON UPDATE NO ACTION
;

ALTER TABLE `Admins` ADD CONSTRAINT `Relationship7ko` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
;