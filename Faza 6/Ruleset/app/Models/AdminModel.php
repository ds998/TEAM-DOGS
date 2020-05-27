<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
        protected $table      = 'admins';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
        protected $allowedFields = ['idUser'];

        public function addAdmin($idUser, $currentUser){

                if(!checkIfAdmin($currentUser))return -1;

                $sameAdmin = $this->where('idUser',$idUser)->findAll();

                if($sameAdmin == null)return -1;

                $data = array(
                        'idUser' => $idUser
                );
                return $this->db->insert($table,$data);
        }

        public function checkIfAdmin($idUser){
                return null;
        }
}