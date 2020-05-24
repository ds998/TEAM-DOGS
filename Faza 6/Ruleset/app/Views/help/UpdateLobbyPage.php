<?php
use App\Models\LobbyModel;
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $lid = $_GET['id'];
}
$idLobby=json_decode($lid);
$lobbyModel=new LobbyModel();
$lobby=$lobbyModel->find($idLobby);
$str="";
$inGame=$lobby->inGame;
if($inGame==1) $str=$str."Game: In progress";
else $str=$str."Game: Waiting";
$str=$str.",";
$pl=$lobby->PlayerList;
$str=$str.$pl;
echo $str;
exit;

