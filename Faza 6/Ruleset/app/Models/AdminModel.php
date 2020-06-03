<?php namespace App\Models;
/**
* AdminModel.php – fajl za klasu AdminModel
*  Uros Ugrinic 2017/0714; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* AdminModel – klasa za pristup tabeli chat od baze
*
* @version 1.0
*/
class AdminModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'admins';
        /**
        * @var string $primaryKey PrimaryKey
        */
        protected $primaryKey = 'idUser';
        /**
        * @var string $returnType returnType
        */
        protected $returnType = 'object';
        /**
        * @var array $allowedFields allowedFields
        */
        protected $allowedFields = ['idUser'];

        /** Stavlja idUser u tabelu admina 
        * @return void
        * @param integer $idUser idUser
        * @param integer $currentUser currentUser
        */
        public function registerAdmin($idUser, $currentUser){

                $data = array(
                        'idUser' => $idUser
                );
                $this->insert($data);
        }

        /** proverava da li je zadati idUser admin 
        * @return integer
        * @param integer $idUser idUser
        */
        public function checkIfAdmin($idUser){
                if($this->where('idUser',$idUser)->findAll()==null)return 0;
                return 1;
        }
}