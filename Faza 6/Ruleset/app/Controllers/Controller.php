<?php namespace App\Controllers;
use App\Models\LobbyModel;
use App\Models\UserModel;
class Controller extends BaseController
{
    protected function show($page,$data){
        echo view('navbar');
        echo view("pages/{$page}",$data);
    }
    public function index(){
        $this->session->set('controller','Controller');
        $userModel=new UserModel();
        
        $data=[
            'username' =>session_id(),
            'email'=> session_id(),
            'passwordHash'=>'who cares',
            'salt'=>'yes',
            'isGuest'=>1
        ];
        $user=$userModel->findByMail(session_id());
        if($user==null){
            $userModel->insert($data);
            $user=$userModel->findByMail(session_id());
        }
        
        $this->session->set('user',$user[0]);//ovde treba da se stavi identitet gosta
        return $this->all_lobbies();
        //radi testiranja
    }

	public function all_lobbies()
	{
		$lobby_model=new LobbyModel();
        $lobbies=$lobby_model->findAll();
        return $this->show('pregled_svih_lobby-a',['lobbies'=>$lobbies,'controller'=>$this->session->get('controller')]);
    }
    
    public function join_lobby($idLobby,$error=null){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        return $this->show('prikljucivanje_lobby-u',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }

    public function joining_lobby($idLobby){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        if($lobby->status==1){
            if($lobby->inGame==1){
                return $this->join_lobby($idLobby,"There is a game in progress currently.");
            }
            $x=$lobby->PlayerList;
            $sx=explode(",",$x);
            if(count($sx)==$lobby->maxPlayers){
                return $this->join_lobby($idLobby,"The lobby is filled out.");
            }
            else{
                $user=$this->session->get('user');
                if($user->username==null){
                    $x=$x.',Guest'.$user->idUser;
                }
                else $x=$x.','.$user->username;
                $data=[
                    'idDeck'=>$lobby->idDeck,
                    'idUser'=>$lobby->idUser,
                    'maxplayers'=>$lobby->maxPlayers,
                    'PlayerList'=>$x,
                    'lobbyName'=>$lobby->lobbyName,
                    'password'=>$lobby->password,
                    'status'=>$lobby->status,
                    'inGame'=>$lobby->inGame
                ];
                $lobby_model->update($idLobby,$data);
                $controller=$this->session->get('controller');
                return redirect()->to(site_url("$controller/lobby/{$idLobby}"));

            }
        }
        else{
            return redirect()->to(site_url("$controller/lozinka/{$idLobby}"));
        }
    }
    public function register($username, $email, $password){
        $userModel = new UserModel();

        $userModel->register($username, $email, password_hash($password, PASSWORD_BCRYPT), $salt);
    }

    public function lobby($idLobby,$error=null){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        return $this->show('lobby',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }

	//--------------------------------------------------------------------

}