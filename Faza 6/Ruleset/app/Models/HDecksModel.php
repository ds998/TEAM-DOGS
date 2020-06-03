<?php namespace App\Models;
/**
* HDecksModel.php – fajl za klasu HDecksModel
*  Danilo Stefanovic 2017/0475; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* HDecksModel – klasa za pristup tabeli hdecks od baze
*
* @version 1.0
*/
class HDecksModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'hdecks';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idDeck', 'orderNum'];
    
        /** azurira hdecks tabelu 
        * @return void
        * @param integer $newID newID
        * @param integer $order order
        */
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

        /** vraca sve istaknute spilove 
        * @return function findAll
        */
        public function getAll(){
            return $this->findAll();
        }
}  
