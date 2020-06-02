<?php namespace App\Models;
use CodeIgniter\Model;
class ChatModel extends Model
{
    protected $table      = 'chat';
    protected $primaryKey = 'idLobby';
    protected $returnType = 'object';
    protected $allowedFields = ['idLobby','chat'];
}