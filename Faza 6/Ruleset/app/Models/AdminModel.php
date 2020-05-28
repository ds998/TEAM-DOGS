<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
        protected $table      = 'admins';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
        protected $allowedFields = ['idUser'];

        public function registerAdmin($idUser, $currentUser){

                $data = array(
                        'idUser' => $idUser
                );
                $this->insert($data);
        }

        public function checkIfAdmin($idUser){
                return 1;
        }
}