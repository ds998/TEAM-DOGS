<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
        protected $table      = 'admins';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
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
        * @return isAdminBool
        * @param integer $idUser idUser
        */
        public function checkIfAdmin($idUser){
                if($this->where('idUser',$idUser)->findAll()==null)return 0;
                return 1;
        }
}