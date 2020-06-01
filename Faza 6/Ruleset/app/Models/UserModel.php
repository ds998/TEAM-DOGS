<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
        protected $table      = 'user';
        protected $primaryKey = 'idUser';
        protected $returnType = 'object';
        protected $allowedFields = ['username','email','passwordHash','isGuest'];

        public function findName($username){
                return $this->where('username',$username)->findAll();
        }

        public function findByMail($email){
                return $this->where('email',$email)->findAll();
        }

        public function register($username, $email, $passwordHash){
                if($this->where('username',$username)->findAll()!=null)return -2;
                if($this->where('email',$email)->findAll()!=null)return -1;
                $data=[
                        'username' => $username,
                        'email'=> $email,
                        'passwordHash'=>$passwordHash,
                        'isGuest'=>0
                ];
                $this->insert($data);
                return 1;
        }
}