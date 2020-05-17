<?php namespace App\Controllers;
use App\Models\LobbyModel;
class Controller extends BaseController
{
    public function index(){
        $lobby_model=new LobbyModel();
        $lobbies=$lobby_model->findAll();
        return view('pregled_svih_lobby-a',['lobbies'=>$lobbies]);
    }

	public function all_lobbies()
	{
		return view('');
	}

	//--------------------------------------------------------------------

}