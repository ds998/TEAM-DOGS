<?php namespace App\Models;
use CodeIgniter\Model;
class DeckModel extends Model
{
        protected $table      = 'deck';
        protected $primaryKey = 'idDeck';
        protected $returnType = 'object';
        protected $allowedFields = ['idUser', 'cardRules', 'Cards', 'descr', 'name', 'globalRules', 'suits', 'Rating', 'numberOfPlays', 'numberOfRatings'];
}