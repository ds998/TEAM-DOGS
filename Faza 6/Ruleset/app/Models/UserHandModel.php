<?php namespace App\Models;

use CodeIgniter\Model;

class UserHandModel extends Model
{
        protected $table      = 'user_hand';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
        protected $allowedFields = ['idLobby', 'idUser','cards'];


        public function getUserHand($idUser){
            return $this->find($idUser)->cards;
        }

        public function getXCards($idUser, $numOfCards){
            $temp = getUserHand($idUser);
            if (strlen($temp)>$numOfCards*2) return substr( $temp, 0, $numOfCards*2);
            else return $temp;
        }

        public function addToUserHand($idUser, $cards){
            $stringEnv = "'";
            $temp = $this->find($idUser)->cards;
            $temp = $temp.$cards;
            $temp = $stringEnv.$temp.$stringEnv;
            $qveri = $this->db->query("UPDATE user_hand SET cards = $temp WHERE idUser = $idUser;");
        }

        public function takeFromUserHand($idUser, $numOfCards){
            $temp = getUserHand($idUser);
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
            
            $temp = $stringEnv.$temp.$stringEnv;
            $qveri = $this->db->query("UPDATE user_hand SET cards = $temp WHERE idUser = $idUser;");
            return $returnInfo;
        }
}