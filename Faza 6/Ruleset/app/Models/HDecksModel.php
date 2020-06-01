<?php namespace App\Models;

use CodeIgniter\Model;

class HDecksModel extends Model
{
        protected $table      = 'hdecks';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'orderNum'];
    
        public function change_HD($newID, $order){
            if(!$this->where('orderNum', 1)->findAll()){
                for($i = 1; $i <= 9; $i++){
                    $data=[
                        'idDeck' => 1,
                        'orderNum'=> $i
                    ];
                    $this->insert($data);
                }
            }
            $qveri = $this->db->query("UPDATE hdecks SET idDeck = $newID WHERE orderNum = $order;");
        }

        public function getAll(){
            return $this->findAll();
        }
}  
