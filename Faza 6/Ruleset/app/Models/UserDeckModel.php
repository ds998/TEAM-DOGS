<?php namespace App\Models;

use CodeIgniter\Model;

class UserDeckModel extends Model
{
        protected $table      = 'user_decks';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'idUser','idCreator','Rating'];
}