-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2020 at 10:59 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ruleset_baza`
--
--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `username`, `email`, `passwordHash`, `isGuest`) VALUES
(1, 'fjioeajiofejio', 'jfioewoifejio@gmail.com', '$2y$10$/Whuop8r9pyGbwYRgxbl9.im89tGrU5aORdleKAthYHdjAhC4eDTy', 0),
(2, 'Guest2', 'c3j8hhqruv0jit7m57n0sjutqtukiurh', 'who cares', 1),
(3, 'ukilica', 'ugjioe@gmail.com', '$2y$10$MOwuNhiTnZjZdrx1VCP3WOrlyxnuQGWDUTFFVO/vB1Wfe/gZvu4L.', 0),
(4, 'Guest4', '72f0auqugvjep8b1jneo56shtdkr8t1s', 'who cares', 1),
(5, 'Guest5', 'bm7d8eal3daq0fr16b3ko0sa5ngdpmh2', 'who cares', 1),
(6, 'Guest6', 'vh2e38idmj0tkkj3iq3vbcv24pmfoe28', 'who cares', 1),
(7, 'Guest7', 'q3mrudhojsllms7bru90el5l62a1ma4t', 'who cares', 1),
(8, 'lmaoJAO', 'jiofeajioafejio@gmail.com', '$2y$10$wWZC5a1WxT4t7qn4T6gOmeQ3XyBFin1HV1qcGPjVhaOdVe/TdCTlq', 0),
(9, 'Guest9', '6uuihdsqneaiijski7t7eh65g6d82g1f', 'who cares', 1),
(10, 'blahboi', 'gjiobofgifio@gmail.com', '$2y$10$cU2Ursm.ODg/jNF/GfOLuuylcigdpVr.oRrik12M8RaBReGanheeK', 0),
(11, 'nosleep', 'javolimdakakim@gmail.com', '$2y$10$K9JHJuLtC4NlkEnxVCydhueXoya.gW2wt6Ohw3I8OkZVKHMFPjOYO', 0),
(12, '123mismofankigangsteri', 'doladolabilya@gmai.com', '$2y$10$ZrmZDFjHxvh8dTBWylZ05uMe4bkALd9F9.iHh/6REOAtuvCBVtpm2', 0),
(13, 'Guest13', 'p1oe49fktg0nr4032ruhrf622p4p9s18', 'who cares', 1),
(14, 'Guest14', '4t7oqoontnonlju6sljaslc8mvnt0uf2', 'who cares', 1),
(15, 'fankfankfanki', 'hellomaBOI@gmail.com', '$2y$10$1kC0ssjsCgbuOR1Jo1fDTuGuzXoQPDjguI.uUm9xybGivxmimzRR.', 0),
(16, 'fafafafafa2', 'ugrinic@gmail.com', '$2y$10$67FUTJmISo01CTdfmk4k5eKGo990l26xrerSoLAq.VrXkIcByVW1y', 0),
(17, 'Guest17', '4flv2n3t6ut2siqpcp6eg8382l2jvpas', 'who cares', 1),
(18, 'caobrt', 'cao@cao.com', '$2y$10$uP/HP4HZyjhFKRwz2TAXn.Tps26oURtLTDVP29abingmWb6SMw3My', 0),
(19, 'Guest19', 'sm3borlpbuq9chgbqp4nj4necq6d93tj', 'who cares', 1),
(20, 'Guest20', 'o52hoo2fbivjfk5qf7ing8r6b2583a0l', 'who cares', 1),
(21, 'caobrt123', 'whenVNCE@gmail.com', '$2y$10$tFsVwI5oL10cyO1xGOiPRe2fJ6npiNktuxwGhdhrxVjP..SQZXpuC', 0),
(22, 'Guest22', 'nr9hjaa65acl8sb0vmd6i7naa6hqm8r9', 'who cares', 1),
(23, 'nevergonnagiveyouup', 'user@mail.com', '$2y$10$x2sw6UZFCnoLemWmJ8AsdeMJezfG1E9IyTm8NOwLeGNMQebjJ3U3S', 0),
(24, 'Guest24', 'dt0v5skq1afj6pvu2vhjmjg8ca3sbiut', 'who cares', 1),
(25, 'Guest25', 'bt9r2dholvuiqn40ks3cstdk41d4a7i0', 'who cares', 1),
(26, 'Guest26', '32snpase5dra3mej579d9pk3feulfur3', 'who cares', 1),
(27, 'fjfjfjfjfjfjfjfj', 'uros@gmail.com', '$2y$10$Rpv3d2DLY2pwlQMRNm1DQurmtVdSb87V31xQYQomVNXn5SKvoIIoC', 0),
(28, 'Guest28', 'n0d2dcagifetsfv56ccma1edkckgrg2p', 'who cares', 1),
(29, 'Guest29', '1fqu6jnumte4fp4tmm9o6jnvsfpo8lfc', 'who cares', 1),
(30, 'Guest30', '96vqob0k9hnar4e29fa17veplgoe93rj', 'who cares', 1);

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`idUser`) VALUES
(23);

--
-- Dumping data for table `deck`
--

INSERT INTO `deck` (`idDeck`, `idUser`, `cardRules`, `name`, `descr`, `Cards`, `globalRules`, `suits`, `Rating`, `numberOfPlays`, `numberOfRatings`) VALUES
(1, 12, '0,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;3,a,5,1,1,1', 'blabla1', 'djuskanje123', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,NotKing', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0),
(4, 1, '1,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;4,a,1,1,1,1,6,0;6,a,1,1,1,1,6,0;6,a,1,1,1,1,6,0', 'Patipipl89j148', 'faafsxcz', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,King', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0),
(5, 1, '1,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;4,a,1,1,1,1,6,0;6,a,1,1,1,1,6,0;6,a,1,1,1,1,6,0', 'FALAFAFAFA', 'BROBROBRO', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,King', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0),
(6, 1, '2,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;5,a,1,1,1,1,6,0', 'DAKISA', '9142098428092184094091', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,King', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0),
(7, 1, '2,a,1,1,1,1,6,0;2,a,1,1,1,1,6,0;5,a,1,1,1,1,6,0;5,a,1,1,1,1,6,0', 'ce gleamo lepe filmove', 'jfiwjifwjifw', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,King', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0),
(8, 1, '0,a,1,1,1,1,6,0;3,a,1,1,1,1,6,0;3,a,5,1,1,1', 'dont give up the fight', 'get up stand up', 'Ace,2,3,4,5,6,7,8,9,10,Jack,Queen,King', '1;2;8;1;7;30;0;1;1;1', '1111', 0, 0, 0);

--
-- Dumping data for table `hdecks`
--

INSERT INTO `hdecks` (`idDeck`, `orderNum`) VALUES
(5, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9);


--
-- Dumping data for table `user_decks`
--

INSERT INTO `user_decks` (`idDeck`, `idUser`, `idCreator`, `Rating`) VALUES
(4, 23, 1, 5),
(6, 16, 1, 5),
(7, 23, 1, 5),
(8, 23, 1, 5);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
