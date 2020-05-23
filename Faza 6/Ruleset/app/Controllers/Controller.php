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
            'username' =>null,
            'email'=> $this->session->sessionId(),
            'passwordHash'=>'who cares',
            'salt'=>'yes',
            'isGuest'=>1
        ];
        $userModel->insert($data);
        $user=$userModel->findByMail($this->session->sessionId());
        
        $this->session->set('user',$user);//ovde treba da se stavi identitet gosta
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
        return $this->show('join_lobby',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }

    public function joining_lobby($idLobby){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        if($lobby->status==1){
            $x=$lobby->PlayerList;
            $sx=$x->str_split(",");
            if(count($sx)==$lobby->MaxPlayers){
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
                $lobby_model->update($idlobby,$data);
                $controller=$this->session->get('controller');
                return redirect()->to(site_url("$controller/lobby/{$idLobby}"));

            }
        }
        else{
            return redirect()->to(site_url("$controller/lozinka/{$idLobby}"));
        }
    }

	//--------------------------------------------------------------------

}