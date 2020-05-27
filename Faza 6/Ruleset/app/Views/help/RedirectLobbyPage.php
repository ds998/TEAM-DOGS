<?php
use App\Models\LobbyModel;
if(isset($_GET['args']) && !empty($_GET['args'])) {
    $largs = $_GET['args'];
}
$arr=json_decode($largs);
$controller=$arr->{'controller'};
$lobbyName=$arr->{'lobbyName'};
$lobbyModel=new LobbyModel();
$lobby=$lobbyModel->findByName($lobbyName);
echo base_url("$controller\{$lobby->idLobby}");
exit;