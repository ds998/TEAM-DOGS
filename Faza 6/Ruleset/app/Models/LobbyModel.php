<?php namespace App\Models;

use CodeIgniter\Model;

class LobbyModel extends Model
{
        protected $table      = 'lobby';
        protected $primaryKey = 'idLobby';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'idSession','maxPlayers','lobbyName','password'];
}