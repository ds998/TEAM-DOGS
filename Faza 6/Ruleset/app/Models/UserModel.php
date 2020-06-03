<?php namespace App\Models;
/**
* UserModel.php – fajl za klasu UserModel
*  Danilo Stefanovic 2017/0475; 
* @version 1.0
*/
use CodeIgniter\Model;
/**
* UserModel – klasa za pristup tabeli user od baze
*
* @version 1.0
*/
class UserModel extends Model
{
        /**
        * @var string $table table
        */
        protected $table      = 'user';
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
        protected $allowedFields = ['username','email','passwordHash','isGuest'];
        /** pretrazuje ulaze po polju username
        * 
        * @param string $lobbyName lobbyName
        *
        * @return function findAll
        */
        public function findName($username){
                return $this->where('username',$username)->findAll();
        }
        /** pretrazuje ulaze po polju email
        * 
        * @param string $lobbyName lobbyName
        *
        * @return function findAll
        */
        public function findByMail($email){
                return $this->where('email',$email)->findAll();
        }

        /** dodaje u user tabelu novog korisnika 
        * @return isInserted
        * @param string $username username
        * @param string $email email
        * @param string $passwordHash passwordHash
        */
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