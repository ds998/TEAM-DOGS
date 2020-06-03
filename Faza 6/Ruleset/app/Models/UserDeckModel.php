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

        // nalazi sve sacuvane spilove za nekog korisnika @return user_decks
        public function getUserEntries($idUser){
                return $this->where('idUser',$idUser)->findAll();
        }
}