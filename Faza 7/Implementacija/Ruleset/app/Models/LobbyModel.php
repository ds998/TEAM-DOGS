<?php namespace App\Models;
/**
* LobbyModel.php – fajl za klasu LobbyModel
*  Danilo Stefanovic 2017/0475; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* LobbyModel – klasa za pristup tabeli lobby od baze
*
* @version 1.0
*/
class LobbyModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'lobby';
        /**
        * @var string $returnType returnType
        */
        protected $primaryKey = 'idLobby';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idDeck', 'idUser','maxPlayers','PlayerList','lobbyName','password','status','inGame'];

        /** pretrazuje ulaze po imenu 
        * 
        * @param string $lobbyName lobbyName
        *
        * @return function findAll
        */
        public function findByName($lobbyName){
                return $this->where('lobbyName',$lobbyName)->findAll();
        }
        
}