<?php namespace App\Models;

use CodeIgniter\Model;

class UserDeckModel extends Model
{
        protected $table      = 'user_decks';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'idUser','idCreator','Rating'];

        public function getEntry($idUser,$idDeck){
                return $this->where('idDeck',$idDeck)->where('idUser',$idUser)->findAll();
        }

        public function getUserEntries($idUser){
                return $this->where('idUser',$idUser)->findAll();
        }
}