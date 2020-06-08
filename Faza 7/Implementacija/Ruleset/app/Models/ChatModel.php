<?php namespace App\Models;
/**
* ChatModel.php – fajl za klasu AdminModel
*  Uros Ugrinic 2017/0714; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* ChatModel – klasa za pristup tabeli chat od baze
*
* @version 1.0
*/
class ChatModel extends Model
{
    /**
    * @var string $table table
    */
    protected $table      = 'chat';
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
    protected $allowedFields = ['idLobby','chat'];
}