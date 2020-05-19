<?php namespace App\Controllers;
use App\Models\LobbyModel;
class Controller extends BaseController
{
    public function index(){
        $this->session->set('controller','Controller');
        $this->session->set('id',0);//ovde treba da se stavi identitet gosta
        return $this->all_lobbies();
        //radi testiranja
    }

	public function all_lobbies()
	{
		$lobby_model=new LobbyModel();
        $lobbies=$lobby_model->findAll();
        return view('pregled_svih_lobby-a',['lobbies'=>$lobbies,'controller'=>$this->session->get('controller')]);
    }
    
    public function join_lobby($idLobby){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($id);
        return view('join_lobby',['lobby'=>$lobby,'controller'=>$this->session->get('controller')]);
    }

	//--------------------------------------------------------------------

}