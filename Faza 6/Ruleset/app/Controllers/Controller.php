<?php namespace App\Controllers;
/**
* Controller.php – fajl za opstu Controller klasu
* Danilo Stefanovic 2017/0475; Maja Dimitrijevic 2017/0723; Uros Ugrinic 2017/0714; Damjan Pavlovic 2017/0312
* Posto su deljeni kontroleri po kategorijama, a svako je imao svoje f-je u klasama Controller i UserController,navedeno je svacije vlasnistvo
* @version 1.0
*/
use App\Models\LobbyModel;
use App\Models\UserModel;
use App\Models\GameUpdateModel;
use App\Models\LobbyDeckModel;
use App\Models\UserHandModel;
use App\Models\DeckModel;
/**
* Controller – opsta Controller klasa koja sadrzi funkcije za sve kategorije korisnika
* 
* @version 1.0
*/
class Controller extends BaseController
{
    /**
    * Prikazivanje prikaza
    *
    * @return void
    */
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
            $data=[
                'username' =>"Guest".$user[0]->idUser,
                'email'=> session_id(),
                'passwordHash'=>'who cares',
                'isGuest'=>1
            ];
            $userModel->update($user[0]->idUser,$data);
            $user=$userModel->findByMail(session_id());

        }
        
        $this->session->set('user',$user[0]);//ovde treba da se stavi identitet gosta
        return $this->all_lobbies();
        //radi testiranja
    }

    public function getDecks()
    {
        return json_encode((new DeckModel)->findAll());
    }

    public function listDecks()
    {
        $deckModel = new DeckModel();
        $decks = $deckModel->findAll();
        return $this->show('deckList', ['decks'=>$decks]);
    }
    public function listUserDecks()
    {
        $idUser = $_SESSION['user']->idUser;
        $userDeckModel = new UserDeckModel();
        $decks = $userDeckModel->query('select u.username, d.rating from user u, user_decks d where u.idUser=d.idUser')->findAll();
        return $this->show('userDeckList', ['decks'=>$decks]);
    }
    /**
    * Prikazivanje prikaza pregleda svih lobby-a
    *
    * @return function show
    */
	public function all_lobbies()
	{
		$lobby_model=new LobbyModel();
        $lobbies=$lobby_model->findAll();
        return $this->show('pregled_svih_lobby-a',['lobbies'=>$lobbies,'controller'=>$this->session->get('controller')]);
    }
    /**
    * Prikazivanje prikaza prikljucivanja lobby-a
    *
    * @param integer $idLobby idLobby
    * @param string $error error
    * @return function show
    */
    public function join_lobby($idLobby,$error=null){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        return $this->show('prikljucivanje_lobby-u',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }
    /**
    * Pokusaj prikljucivanja lobby-u
    *
    * @param integer $idLobby idLobby
    * 
    * @return function show or function redirect
    */
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
    /**
    * Prikazivanje prikaza lobby
    *
    * @param integer $idLobby idLobby
    * @param string $error error
    * 
    * @return function show 
    */
    public function lobby($idLobby,$error=null){
        $lobby_model=new LobbyModel();
        $lobby=$lobby_model->find($idLobby);
        return $this->show('lobby',['lobby'=>$lobby,'controller'=>$this->session->get('controller'),'error'=>$error]);
    }
    /**
    * Azuriranje lobby-a iz baze za prikaz pregled svih lobby-a
    *
    * 
    * @return json 
    */
    public function update_lobbies(){
        $lobbyModel=new LobbyModel();
        return json_encode($lobbyModel->findAll());
    }
    /**
    * Azuriranje lobby-a iz baze za prikaz pregled svih lobby-a
    *
    * @param integer $idLobby idLobby
    * 
    * @return json 
    */
    public function update_lobby($idLobby){
        $lobbyModel=new LobbyModel();
        $lobby=$lobbyModel->find($idLobby);
        if($lobby==null){
            return json_encode("Nothing!");
        }
        else return json_encode($lobby->PlayerList);
    }
    /**
    * Izlazak iz lobby-a
    *
    * 
    * @return function redirect
    */
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
    /**
    * Ucitavanje igre
    *
    * 
    * @return function show
    */
    public function game($idLobby){
        $lobbyModel=new LobbyModel();
        $userModel=new UserModel();
        $lobby=$lobbyModel->find($idLobby);
        
        $this_user=$this->session->get('user');
        if($lobby->idUser==$this_user->idUser){
            $gameUpdateModel=new GameUpdateModel();
            $data=[
                'idLobby'=>$idLobby,
                'idUser'=>$this_user->idUser,
                'updateF'=>"claimed,".$this_user->idUser.";"
            ];
            $gameUpdateModel->insert($data);
            $this->fill_and_shuffle($idLobby);

        }
        $userHandModel=new UserHandModel();
        $data=[
            'idLobby'=>$idLobby,
            'idUser'=>$this_user->idUser,
            'cards'=>""
        ];
        $userHandModel->insert($data);
        $players=array();
        $pl=explode(",",$lobby->PlayerList);
        for($i=0;$i<count($pl);$i++){
            $ll=$userModel->findByName($pl[$i]);
            array_push($players,$ll[0]->idUser);
        }
        return $this->show('game',['controller'=>$this->session->get('controller'),'players'=>$players,'idLobby'=>$idLobby]);
        

    }
    /**
    * Punjenje spila za igru
    *
    * 
    * @return void
    */
    public function fill_and_shuffle($idLobby){
        $deckModel=new DeckModel();
        $lobbyModel=new LobbyModel();
        $lobbyDeckModel=new LobbyDeckModel();
        $lobby=$lobbymodel->find($idLobby);
        $deck=$deckModel->find($lobby->idDeck);
        $cards=$deck->Cards;
        $suits=$deck->suits;
        $c=explode(",",$cards);
        $glob_rules=$deck->globalRules;
        $glr=explode(";",$glob_rules);
        $num_of_decks=intval($glr[2]);
        $arr=array();
        for($i=0;$i<$num_of_decks;$i++){
            for($j=0;$j<count($c);$j++){
                $str="";
                if($suits[0]=='1'){
                    $str=chr($j).'c';
                    array_push($arr,$str);
                }
                if($suits[1]=='1'){
                    $str=chr($j).'d';
                    array_push($arr,$str);
                }
                if($suits[2]=='1'){
                    $str=chr($j).'s';
                    array_push($arr,$str);
                }
                if($suits[3]=='1'){
                    $str=chr($j).'h';
                    array_push($arr,$str);
                }
            }
        }
        shuffle($arr);
        $new_cards=implode("",$arr);
        $old_entry=$lobbyDeckModel->find($id);
        if($old_entry==null){
            $data=[
                'idLobby'=>$idLobby,
                'cards'=>$new_cards
            ];
            $lobbyDeckModel->insert($data);
        }
        else{
            $data=[
                'cards'=>$new_cards
            ];
            $lobbyDeckModel->update($idLobby,$data);
        }

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
                return json_encode($cards);
            }
            else if ($idUserThrown == $idSource) return null; // greska ne mozes da vuces iz svog spila
            else // vuce od drugog korisnika
            {
                $cards = $userHandModel->takeFromUserHand($idSource, $numOfCards); // uzmemo karte iz ruke $idSource
                $userHandModel->addToUserHand($idUserAffected, $cards); // dodamo $idUserAffected
                return json_encode($cards);
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
        return json_encode($cardsToView);
    }

    public function update($idLobby)
    {
        return json_encode((new GameUpdateModel())->getUpdate($idLobby));
    }

    public function myHand($idUser)
    {
        return json_encode((new UserHandModel())->getUserHand($idUser));
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