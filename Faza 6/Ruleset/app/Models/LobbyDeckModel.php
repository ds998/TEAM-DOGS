<?php namespace App\Models;

use CodeIgniter\Model;

class LobbyDeckModel extends Model
{
        protected $table      = 'lobby_deck';
        protected $primaryKey = 'idLobby';
        protected $returnType = 'object';
        protected $allowedFields = ['idLobby','cards'];

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

        public function getDeck($idLobby){
            return $this->find($idLobby)->cards;
        }

        public function getXCards($idLobby){
            $temp = $this->getDeck($idLobby);
            if (strlen($temp)>$numOfCards*2) return substr( $temp, 0, $numOfCards*2);
            else return $temp;
        }
}