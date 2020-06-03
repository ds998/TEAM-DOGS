<?php namespace App\Models;
/**
* UserHandModel.php – fajl za klasu UserHandModel
*  Uros Ugrinic 2017/0714; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* UserHandModel – klasa za pristup tabeli user_hand od baze
*
* @version 1.0
*/
class UserHandModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'user_hand';
        /**
        * @var string $primaryKey PrimaryKey
        */
        protected $primaryKey = 'idUser';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idLobby', 'idUser','cards'];


        /** vraca karte iz ruke zeljenog korisnika 
        * @return cards
        * @param integer $idUser idUser
        */
        public function getUserHand($idUser){
            return $this->find($idUser)->cards;
        }

        /** vraca x karata od nekog korisnika 
        * @return cards
        * @param integer $idUser idUser
        * @param integer $numOfCards numOfCards
        */
        public function getXCards($idUser, $numOfCards){
            $temp = $this->getUserHand($idUser);
            if (strlen($temp)>$numOfCards*2) return substr( $temp, 0, $numOfCards*2);
            else return $temp;
        }

        /** dodaje korisniku karte 
        * @return void
        * @param integer $idUser idUser
        * @param string $cards cards
        */
        public function addToUserHand($idUser, $cards){
            $stringEnv = "'";
            $temp = $this->find($idUser)->cards;
            $temp = $temp.$cards;
            $temp = $stringEnv.$temp.$stringEnv;
            $qveri = $this->db->query("UPDATE user_hand SET cards = $temp WHERE idUser = $idUser;");
        }

        /** uzima iz ruke korisnika x broj karata
        * @return takenCards 
        * @param integer $idUser idUser
        * @param integer $numOfCards numOfCards
        */
        public function takeFromUserHand($idUser, $numOfCards){
            $temp = $this->getUserHand($idUser);
            if(strlen($temp)>$numOfCards*2)
            {
                $returnInfo = substr( $temp, 0, $numOfCards*2);
                $temp = substr($temp, $numOfCards*2, strlen($temp));
            }
            else 
            {
                $returnInfo = $temp;
                $temp = "''";
            }
            $stringEnv = "'";
            $temp = $stringEnv.$temp.$stringEnv;
            $qveri = $this->db->query("UPDATE user_hand SET cards = $temp WHERE idUser = $idUser;");
            return $returnInfo;
        }
}