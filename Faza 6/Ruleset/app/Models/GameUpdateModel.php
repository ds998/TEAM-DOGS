<?php namespace App\Models;

use CodeIgniter\Model;

class GameUpdateModel extends Model
{
        protected $table      = 'game_update';
        protected $primaryKey = 'idLobby';
        protected $returnType = 'object';
        protected $allowedFields = ['idLobby', 'idUser', 'updateF'];

        // azurira gameupdate tabelu @return void 
        public function addToUpdate($idLobby, $update){
            $temp = $this->find($idLobby)->updateF;
            $temp = "'".$temp.$update."'";
            $qveri = $this->db->query("UPDATE game_update SET updateF = $temp WHERE idLobby = $idLobby;");
        }

        // vraca trenutni update za neki idLobby @return update
        public function getUpdate($idLobby){
            return $this->find($idLobby)->updateF;
        }

        // azurira trenutni update na zeljenog korisnika i zeljeni string @return void
        public function newUserUpdate($idUser, $update, $idLobby){
            $temp = "'".$update."'";
            $qveri = $this->db->query("UPDATE game_update SET idUser = $idUser, updateF = $temp WHERE idLobby = $idLobby;");
        }
}