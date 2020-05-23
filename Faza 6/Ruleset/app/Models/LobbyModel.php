<?php namespace App\Models;

use CodeIgniter\Model;

class LobbyModel extends Model
{
        protected $table      = 'lobby';
        protected $primaryKey = 'idLobby';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'idUser','maxPlayers','PlayerList','lobbyName','password','status','inGame'];

        public function findByName($lobbyName){
                return $this->where('lobbyName',$lobbyName)->findAll();
        }
        
}