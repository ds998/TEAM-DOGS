<?php
use App\Models\LobbyModel;
if(isset($_GET['stuff']) && !empty($_GET['stuff'])) {
    $largs = $_GET['stuff'];
}
$arr=json_decode($largs);
$controller=$arr->{'controller'};
$lobbyName=$arr->{'lobbyName'};
$lobbyModel=new LobbyModel();
$lobby=$lobbyModel->findByName($lobbyName);
echo base_url("$controller\{$lobby->idLobby}");
exit;