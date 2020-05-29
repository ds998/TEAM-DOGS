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
                    'maxPlayers'=>$lobby->maxPlayers,
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

    public function lobby($idLobby,$error=null){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        return $this->show('lobby',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }
    public function update_lobbies(){
        $lobbyModel=new LobbyModel();
        return json_encode($lobbyModel->findAll());
    }
    public function update_lobby($idLobby){
        $lobbyModel=new LobbyModel();
        $lobby=$lobbyModel->find($idLobby);
        if($lobby==null){
            return json_encode("Nothing!");
        }
        else return json_encode($lobby->PlayerList);
    }
    public function exit_lobby($idLobby){
        $lobbyModel=new LobbyModel();
        $user=$this->session->get('user');
        $lobby=$lobbyModel->find($idLobby);
        if($user->idUser==$lobby->idUser){
            $lobbyModel->delete($idLobby);
        }
        else{
            $string=$lobby->PlayerList;
            $players=explode(",",$string);
            $new_str="";
            foreach($players as $player){
                if($user->username!=$player) {
                    if($new_str!="") $new_str=$new_str.",";
                    $new_str=$new_str.$player;
                }
            }
            $data=[
                'idDeck'=>$lobby->idDeck,
                'idUser'=>$lobby->idUser,
                'maxPlayers'=>$lobby->maxPlayers,
                'PlayerList'=>$new_str,
                'lobbyName'=>$lobby->lobbyName,
                'password'=>$lobby->password,
                'status'=>$lobby->status,
                'inGame'=>$lobby->inGame
            ];
            $lobbyModel->update($idLobby,$data);

        }
        $controller=$this->session->get('controller');
        return redirect()->to(site_url("$controller/all_lobbies"));
    }

    //--------------------------------------------------------------------
    
    //-------------GAME RELATED-------------------------------------------
    public function draw( $idUserThrown, $idUserAffected, $numOfCards, $idSource, $cardThrown, $idLobby)
    {
        // update poteza koji trenutno $idUserThrown igra da bi svi znali sta se radi u igri (ali ne vide koje se karte vuku itd)
        $update = "draw";
        $update = $update.",".$idUserThrown.",".$idUserAffected.",".$numOfCards.",".$idSource.",".$cardThrown.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);

        if($idUserThrown == $idUserAffected) // true znaci da korisnik nema cime da preklopi draw pa sam sebi kaze da mora da vuce
        { 
            $userHandModel = new UserHandModel();
            if($idSource == 0) // znaci da se vuce iz spila
            {
                $LobbyDeckModel = new LobbyDeckModel();
                $cards = $LobbyDeckModel->takeXCards($idLobby, $numOfCards);
                $userHandModel->addToUserHand($idUserAffected, $cards);
                return $cards;
            }
            else if ($idUserThrown == $idSource) return null; // greska ne mozes da vuces iz svog spila
            else // vuce od drugog korisnika
            {
                $cards = $userHandModel->takeFromUserHand($idSource, $numOfCards); // uzmemo karte iz ruke $idSource
                $userHandModel->addToUserHand($idUserAffected, $cards); // dodamo $idUserAffected
                return $cards;
            }

        }
        else return null;
    
    }

    public function skip($idUser, $idLobby)
    {
        $update = "skip";
        $update = $update.",".$idUser.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);  
        // ideja je da ce korisnik koji treba da bude preskocen da vidi da treba da bude preskocen 
        // i kada dodje red na njega on samo moze da zavrsi potez
    }

    public function viewCard($idUserThrown, $idSource, $num, $idLobby)
    {
        $update = "skip";
        $update = $update.",".$idUser.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update); // klasican update

        $userHandModel = new UserHandModel();
        $cardsToView = $userHandModel->getXCards($idSource, $num);
        return $cardsToView;
    }

    public function update($idLobby)
    {
        return (new GameUpdateModel())->getUpdate($idLobby);
    }

    public function myHand($idUser)
    {
        return (new UserHandModel())->getUserHand($idUser);
    }

    public function endTurn($idLobby)
    {
        $update = "endTurn,".$idUser.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

    public function claimTurn($idUser, $idLobby, $cardThrown)
    {
        $gum = new GameUpdateModel();
        $update = $gum->getUpdate($idLobby);
        $pos = strpos($update, "endTurn");
        if($pos === false) return false;
        else 
        {
            $update = "claimed,".$idUser.";";
            $gum->newUserUpdate($idUser, $update, $idLobby);
        }
        return true;
    }

    public function changeGlobalRule( $rule, $newValue, $idLobby)
    {
        $update = "cgr,".$rule.",".$newValue.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

    public function throw($idUser, $card, $idLobby)
    {
        $userHandModel = new UserHandModel();
        $userHandModel->takeSpecificCard($idUserThrown, $card);
        $update = "throw,".$idUser.",".$card.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

}