<?php namespace App\Models;

use CodeIgniter\Model;

class HDecksModel extends Model
{
        protected $table      = 'hdecks';
        protected $returnType = 'object';
        protected $allowedFields = ['idDeck', 'orderNum'];
    
        public function change_HD($newID, $order){
            if(!$this->where('orderNum', $order)->findAll()){
                    $data=[
                        'idDeck' => $newID,
                        'orderNum'=> $order
                    ];
                    $this->insert($data);
                
            }
            else{
                $qveri = $this->db->query("UPDATE hdecks SET idDeck = $newID WHERE orderNum = $order;");
            }
        }

        public function getAll(){
            return $this->findAll();
        }
}  
