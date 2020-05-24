<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
        protected $table      = 'user';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
        protected $allowedFields = ['username','email','passwordHash','salt','isGuest'];

        public function findName($username){
                return $this->where('username',$username)->findAll();
        }

        public function findByMail($email){
                return $this->where('email',$email)->findAll();
        }
}