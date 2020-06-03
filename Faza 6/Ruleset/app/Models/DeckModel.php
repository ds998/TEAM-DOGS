<?php namespace App\Models;
/**
* DeckModel.php – fajl za klasu DeckModel
*  Danilo Stefanovic 2017/0475; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* DeckModel – klasa za pristup tabeli deck od baze
*
* @version 1.0
*/
class DeckModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'deck';
        /**
        * @var string $primaryKey PrimaryKey
        */
        protected $primaryKey = 'idDeck';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idUser', 'cardRules', 'Cards', 'name','descr', 'globalRules', 'suits', 'Rating', 'numberOfPlays', 'numberOfRatings'];
}