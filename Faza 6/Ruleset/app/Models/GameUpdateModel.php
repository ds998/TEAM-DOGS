<?php namespace App\Models;
/**
* GameUpdateModel.php – fajl za klasu GameUpdateModel
*  Uros Ugrinic 2017/0714; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* GameUpdateModel – klasa za pristup tabeli game_update od baze
*
* @version 1.0
*/
class GameUpdateModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'game_update';
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
        protected $allowedFields = ['idLobby', 'idUser', 'updateF'];

        /** azurira gameupdate tabelu 
        * @return void 
        * @param integer $idLobby idLobby
        * @param string $update update
        */
        public function addToUpdate($idLobby, $update){
            $temp = $this->find($idLobby)->updateF;
            $temp = "'".$temp.$update."'";
            $qveri = $this->db->query("UPDATE game_update SET updateF = $temp WHERE idLobby = $idLobby;");
        }

        /** vraca trenutni update za neki idLobby 
        * @return update
        * @param integer $idLobby idLobby
        */
        public function getUpdate($idLobby){
            return $this->find($idLobby)->updateF;
        }

        /** azurira trenutni update na zeljenog korisnika i zeljeni string 
        * @return void
        * @param integer $idUser idUser
        * @param string $update update
        * @param integer $idLobby idLobby
        */
        public function newUserUpdate($idUser, $update, $idLobby){
            $temp = "'".$update."'";
            $qveri = $this->db->query("UPDATE game_update SET idUser = $idUser, updateF = $temp WHERE idLobby = $idLobby;");
        }
}