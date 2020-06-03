<?php namespace App\Models;
/**
* UserDeckModel.php – fajl za klasu UserDeckModel
*  Danilo Stefanovic 2017/0475; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* UserDeckModel – klasa za pristup tabeli user_decks od baze
*
* @version 1.0
*/
class UserDeckModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'user_decks';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idDeck', 'idUser','idCreator','Rating'];

        public function getEntry($idUser,$idDeck){
                return $this->where('idDeck',$idDeck)->where('idUser',$idUser)->findAll();
        }

        /** nalazi sve sacuvane spilove za nekog korisnika 
        * 
        * @param integer $idUser idUser
        * @return function findAll
        */
        public function getUserEntries($idUser){
                return $this->where('idUser',$idUser)->findAll();
        }
}