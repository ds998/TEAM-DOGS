<?php namespace App\Models;
/**
* LobbyDeckModel.php – fajl za klasu LobbyDeckModel
*  Uros Ugrinic 2017/0714; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* LobbyDecksModel – klasa za pristup tabeli lobby_deck od baze
*
* @version 1.0
*/
class LobbyDeckModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'lobby_deck';
        /**
        * @var string $primaryKey PrimaryKey
        */
        protected $primaryKey = 'idLobby';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idLobby','cards'];

        /** uzima x karata iz spila 
        * @return cards
        * @param integer $idLobby idLobby
        * @param integer $numOfCards numOfCards
        */
        public function takeXCards($idLobby, $numOfCards){
            $temp = $this->getDeck($idLobby);
            $stringEnv = "'";
            if (strlen($temp)>$numOfCards*2)
            {
                $returnInfo = substr( $temp, 0, $numOfCards*2);
                $temp = substr($temp, $numOfCards*2, strlen($temp));
            }
            else 
            {
                $returnInfo = $temp;
                $temp = "''";
            }
            
            $temp = $stringEnv.$temp.$stringEnv;
            $qveri = $this->db->query("UPDATE lobby_deck SET cards = $temp WHERE idLobby = $idLobby;");
            return $returnInfo;
        }

        
        /** uzima sve karte iz spila 
        * @return cards
        * @param integer $idLobby idLobby
        */
        public function getDeck($idLobby){
            return $this->find($idLobby)->cards;
        }

        /** vraca x karata iz spila (ali ih ne uzima) 
        * @return cards
        * @param integer $idLobby idLobby
        */
        public function getXCards($idLobby, $numOfCards){
            $temp = $this->getDeck($idLobby);
            if (strlen($temp)>$numOfCards*2) return substr($temp, -$numOfCards*2);
            else return $temp;
        }
}