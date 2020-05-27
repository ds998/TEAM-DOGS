<?php namespace App\Controllers;
use App\Models\LobbyModel;

class Dummy extends BaseController{
    public function func(){
        if(isset($_POST['lobbyName']) && isset($_POST['control'])){
            $lobbyName=$_POST['lobbyName'];
            $controller=$_POST['control'];
            $lobbyModel=new LobbyModel();
            $lobby=$lobbyModel->findByName($lobbyName);
            $idLobby=$lobby[0]->idLobby;
            echo json_encode(base_url($controller."/join_lobby/".$idLobby));
            
        }

        
    }
}