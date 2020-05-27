<?php namespace App\Models;

use CodeIgniter\Model;

class HDecksModel extends Model
{
        protected $table      = 'hdecks';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'orderNum'];
    
        public function changeHD($newID, $order){
            $qveri = $this->db->query("UPDATE hdecks SET idDeck = $newID, orderNum = $order WHERE orderNum = $order;");
        }

        public function getAll(){
            return $this->findAll();
        }
}  
