<?php namespace App\Controllers;
/**
* Controller.php – fajl za opstu Controller klasu
* Danilo Stefanovic 2017/0475; Maja Dimitrijevic 2017/0723; Uros Ugrinic 2017/0714; Damjan Pavlovic 2017/0312
* Posto su deljeni kontroleri po kategorijama, a svako je imao svoje f-je u klasama Controller i UserController,navedeno je svacije vlasnistvo
* @version 1.0
*/
use App\Models\LobbyModel;
use App\Models\UserModel;
use App\Models\ChatModel;
use App\Models\GameUpdateModel;
use App\Models\LobbyDeckModel;
use App\Models\UserHandModel;
use App\Models\DeckModel;
use App\Models\UserDeckModel;
use App\Models\AdminModel;
use App\Models\HDecksModel;
/**
* Controller – opsta Controller klasa koja sadrzi funkcije za sve kategorije korisnika
* 
* @version 1.0
*/
class Controller extends BaseController
{
    /**
    * Prikazivanje stranice
    *
    * @return void
    */
    protected function show($page,$data){
        echo view("pages/{$page}",$data);
    }
    /**
    * Prikazivanje pocetne stranice za Controller
    *
    * @return function show
    */
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

        $_SESSION['user'] = $user[0];
        $hdecksModel = new HDecksModel();
        $hdecks = $hdecksModel->query(" select decc.name, decc.iddeck, hd.orderNum
                                        from deck decc, hdecks hd
                                        where hd.idDeck=decc.idDeck");
        $hdecks = $hdecks->getResult();
        return $this->show("main",['controller'=>$this->session->get('controller'), 'hdecks' => $hdecks]);
    }

    /** registruje korisnika (ako vec nije registrovan i ako je unique email i username) 
    * @return stranicaZaReq || @return redirectToUserMainMenu
    */
    public function register()
    {
        if($this->request->getVar('username'))
		{
            $username = $this->request->getVar('username');
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            $userModel = new UserModel();
            $response = $userModel->register($username, $email, password_hash($password, PASSWORD_BCRYPT));
            $idUser = ($userModel->findName($username))[0]->idUser;
            if($response == -1 || $response == -2)return $this->show('register',['controller'=>$this->session->get('controller')]);
            $adminModel=new AdminModel();
            $controller="";
            $isAdmin=$adminModel->find($idUser);
            if($isAdmin!=null){
                $controller="AdminController";
            }
            else{
                $controller="UserController";
            }
            return redirect()->to(site_url("$controller/index/$idUser"));
        }
        else return $this->show('register',['controller'=>$this->session->get('controller')]);
    }

    public function getDecks()
    {
        return json_encode((new DeckModel)->findAll());
    }

    
    /**  nalazi i pokazuje sve spilove 
    * @return deckListStranica
    */
    public function decklist()
    {
        $deckModel = new DeckModel();
        $decks = $deckModel->findAll();
        $controller=$this->session->get('controller');
        return $this->show('deckList', ['decks'=>$decks,'controller'=>$controller]);
    }

    /**  nalazi i pokazuje prikaz spila
    * @return deckPreviewStranica
    * @param integer $idDeck idDeck
    */
    public function deckPreview($idDeck)
    {
        $deckModel = new DeckModel();
        $userModel = new UserModel();
        $deck = $deckModel->find($idDeck);
        $user = $this->session->get('user');
        $name = $userModel->query(" select u.username
                                        from user u
                                        where u.idUser=$deck->idUser");
        $name = $name->getResult();
        $name = $name[0]->username;
        return $this->show('deckPreview', 
            ['deck'=>$deck, 'user' => $this->session->get('user'), 'controller'=>$this->session->get('controller'),'username'=>$name]);
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
            $controller = $this->session->get('controller');
            return redirect()->to(site_url("$controller/lobby_password_page/{$idLobby}"));
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
    * Prikaz login stranice
    *
    * 
    * @return function show
    *Maja Dimitrijevic 2017/0723
    */
    public function login_page($error=null) {
        $controller=$this->session->get('controller');
        return $this->show('login',['controller'=>$this->session->get('controller'),'error'=>$error]);
    }

    /**
    * Pokusaj logovanja gosta
    *
    * 
    * @return function login_page or redirect
    *Maja Dimitrijevic 2017/0723
    */
    public function login_submit() {

        $username = $this->request->getVar('user_name');
        $password = $this->request->getVar('user_password');
        $error_msg = "";

        if (empty($username)) {
           $error_msg = "empty username ";
        }
        if (empty($password)) {
            $error_msg .= "empty password";
        }

        if (!($error_msg == "")) {
            return $this->login_page($error_msg);
        }
        else {
            $userModel = new UserModel();
            $user = $userModel->findName($username);
            if ($user != null) {
                $user = $user[0];
            }

            if ($user == null) {
                $this->login_page("Username doesn't exist");
            }
            else {
                if (password_verify($password,$user->passwordHash)) {
                    $controller = "";
                    $adminModel = new AdminModel();
                    $ifAdmin = $adminModel->find($user->idUser);
                    if ($ifAdmin == null) {
                        $controller = "UserController";
                    }
                    else {
                        $controller = "AdminController";
                    }
                    $this->session->set('user', $user);
                    return redirect()->to(site_url("$controller/index/{$user->idUser}"));
                }
                else {
                    $this->login_page('Incorrect password.');
                }
            }
        }

    }

    /**
    * Prikaz stranice za lozinku za pristup lobby-ju
    *
    * 
    * @return function show
    *Maja Dimitrijevic 2017/0723
    */
    public function lobby_password_page($idLobby, $error=null) {
        return $this->show('lobby_password',['idLobby'=>$idLobby, 'error'=>$error, 'controller'=>$this->session->get('controller')]);
    }

    /**
    * Pokusaj pristupanja lobby-ju
    *
    * 
    * @return function lobby_password_page or redirect
    *Maja Dimitrijevic 2017/0723
    */
    public function lobby_password_submit($idLobby) {

        $lobbypassword = $this->request->getVar('lobby_password');
        $error_msg = "";
        if (empty($lobbypassword)) {
           $error_msg = "Empty lobby password field";
        }
        if ($error_msg != "") {
           return $this->lobby_password_page($idLobby, $error_msg);
        }
        else {
            $newLobbyModel = new LobbyModel();
            $findLobby = $newLobbyModel->find($idLobby);

            if ($findLobby->password != $lobbypassword) {
                return $this->lobby_password_page($idLobby, "Incorrect lobby password");
            }
            if ($findLobby->inGame == 1) {
                return $this->lobby_password_page($idLobby, "There is a game in progress");
            }

            $pl = $findLobby->PlayerList;
            $players_array = explode(",", $pl);

            if (count($players_array) == $findLobby->maxPlayers) {
                return $this->lobby_password_page($idLobby, "The lobby is already full");
            }

            $user = $this->session->get('user');

            $data = [
                'idDeck'=>$findLobby->idDeck,
                'idUser'=>$findLobby->idUser,
                'maxPlayers'=>$findLobby->maxPlayers,
                'PlayerList'=>$pl.",".$user->username,
                'lobbyName'=>$findLobby->lobbyName,
                'password'=>$findLobby->password,
                'status'=>$findLobby->status,
                'inGame'=>$findLobby->inGame
            ];

            $newLobbyModel->update($idLobby,$data);

            $controller = $this->session->get('controller');
            return redirect()->to(site_url("$controller/lobby/{$idLobby}"));
        }

    }

    /**
    * Prikaz stranice za kreiranje lobby-ja
    *
    * 
    * @return function show
    *Maja Dimitrijevic 2017/0723
    */
    public function create_lobby_page($idDeck, $error=null) {
        $deckModel = new DeckModel();
        $deck = $deckModel->find($idDeck);
        return $this->show('create_lobby',['error'=>$error, 'controller'=>$this->session->get('controller'), 'idDeck'=>$idDeck, 'deckName'=>$deck->name]);
    }

    /**
    * Pokusaj kreiranja lobby-ja
    *
    * 
    * @return function create_lobby_page or redirect
    *Maja Dimitrijevic 2017/0723
    */
    public function create_lobby_submit($idDeck) {
        $deckModel = new DeckModel();
        $lobbyModel = new LobbyModel();
        $deck = $deckModel->find($idDeck);
        $lobby_name = $this->request->getVar('lobby_name');
        $create_lobby_password = $this->request->getVar('create_lobby_password');
        $private_checkmark = $this->request->getVar('private_checkmark');
        $maxplayercount = $this->request->getVar('create_max_player_count');

        if (empty($lobby_name)) {
            return $this->create_lobby_page($idDeck, "Empty lobby name field");
        }
        if (strlen($lobby_name) > 15) {
            return $this->create_lobby_page($idDeck, "Lobby name must be less than 15 characters long");
        }
        if ($private_checkmark != null && empty($create_lobby_password)) {
            return $this->create_lobby_page($idDeck, "Empty lobby password field");
        }
        if ($maxplayercount < 2 || $maxplayercount > 10) {
            return $this->create_lobby_page($idDeck, "Max Player Count out of range");
        }
        
        $user = $this->session->get('user');
        $status = ($private_checkmark == null)? 1:0;

        $data = [
            'idDeck'=>$idDeck,
            'idUser'=>$user->idUser,
            'maxPlayers'=>$maxplayercount,
            'PlayerList'=>$user->username,
            'lobbyName'=>$lobby_name,
            'password'=>$create_lobby_password,
            'status'=>$status,
            'inGame'=>0
        ];

        $lobbyModel->insert($data);
        $lobby_array = $lobbyModel->findByName($lobby_name); 
        $lobby = $lobby_array[0];

        $controller = $this->session->get('controller');
        return redirect()->to(site_url("$controller/lobby/{$lobby->idLobby}"));

    }
     /**
    * Slanje poruke tokom igre
    *
    * @param integer $idLobby idLobby
    * @param string $message message
    *
    * @return json
    * Danilo Stefanovic 2017/0475
    */
    public function send_message($idLobby, $message) {
        echo $idLobby;
        $user = $this->session->get('user');
        $message = $user->username." : ".$message;
        $chatModel = new ChatModel();
        $chatRoom = $chatModel->find($idLobby);
        if($chatRoom == null) {
            $data = [
                'idLobby'=>$idLobby,
                'chat'=>$message
            ];
            $chatModel = new ChatModel();
            $chatModel->insert($data);
        }
        else {
            $chatString = $chatRoom->chat;
            $chatMessages = explode("^",$chatString);
            array_push($chatMessages, $message);
            $new_str = "";
            if(count($chatMessages) > 15) {
                for($i = 1; $i < count($chatMessages) - 1; $i++) {
                    $new_str = $new_str.$chatMessages[$i]."^";
                }
                $new_str = $new_str.$chatMessages[count($chatMessages) - 1];
            }
            else {
                for($i = 0; $i < count($chatMessages) - 1; $i++) {
                    $new_str = $new_str.$chatMessages[$i]."^";
                }
                $new_str = $new_str.$chatMessages[count($chatMessages) - 1];
            }
            $data = [
                'chat'=>$new_str
            ];
            $chatModel->update($idLobby, $data);
        }
        return json_encode("done");

    }
     /**
    * Azuriranje chat-a tokom igre
    *
    * @param integer $idLobby idLobby
    * 
    * @return json
    *
    * Danilo Stefanovic 2017/0475
    */
    public function chat_update($idLobby) {
        $chatModel = new ChatModel();
        $chatRoom = $chatModel->find($idLobby);
        if ($chatRoom == null) {
            return json_encode([]);
        }
        else {
            $chatMessages = $chatRoom->chat;
            $chatArray = explode("^",$chatMessages);
            return json_encode($chatArray);
        }
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
            $data=[
                'idDeck'=>$lobby->idDeck,
                'idUser'=>$lobby->idUser,
                'maxPlayers'=>$lobby->maxPlayers,
                'PlayerList'=>$lobby->PlayerList,
                'lobbyName'=>$lobby->lobbyName,
                'password'=>$lobby->password,
                'status'=>$lobby->status,
                'inGame'=>1
            ];
            $lobbyModel->update($idLobby,$data);

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
            $ll=$userModel->findName($pl[$i]);
            array_push($players,$ll[0]->idUser);
        }
        $F_DECK=(new DeckModel())->find($lobby->idDeck);
        return $this->show('game',['controller'=>$this->session->get('controller'),'players'=>$players,'idLobby'=>$idLobby, 'idUser'=>$this_user->idUser, 'deck'=>$F_DECK]);
        

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
        $lobby=$lobbyModel->find($idLobby);
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
                    $str=chr($j+64).'c';
                    array_push($arr,$str);
                }
                if($suits[1]=='1'){
                    $str=chr($j+64).'d';
                    array_push($arr,$str);
                }
                if($suits[2]=='1'){
                    $str=chr($j+64).'s';
                    array_push($arr,$str);
                }
                if($suits[3]=='1'){
                    $str=chr($j+64).'h';
                    array_push($arr,$str);
                }
            }
        }
        shuffle($arr);
        $new_cards=implode("",$arr);
        $old_entry=$lobbyDeckModel->find($idLobby);
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
     /**
    * Zavrsavanje igre
    *
    * @param integer $idLobby idLobby
    * 
    *
    * @return function show
    * Danilo Stefanovic 2017/0475
    */
    public function end_game($idLobby){
        $chatModel=new ChatModel();
        $userHandModel=new UserHandModel();
        $gameUpdateModel=new GameUpdateModel();
        $lobbyDeckModel=new LobbyDeckModel();
        $user=$this->session->get('user');
        $chat=$chatModel->find($idLobby);
        if($chat!=null) $chatModel->delete($idLobby);

        $game_update=$gameUpdateModel->find($idLobby);
        if($game_update!=null) $gameUpdateModel->delete($idLobby);

        $lobbyDeck=$lobbyDeckModel->find($idLobby);
        if($lobbyDeck!=null) $lobbyDeckModel->delete($idLobby);

        $userHand=$userHandModel->find($user->idUser);
        if($userHand!=null) $userHandModel->delete($user->idUser);

        return $this->show('endgame_screen',['controller'=>$this->session->get('controller'),'idLobby'=>$idLobby]);
    }
     /**
    * Povratak na lobby tokom kraja igre;moguce ocenjivanje spila
    *
    * @param integer $idLobby idLobby
    * @param integer $rating rating
    *
    * @return function lobby
    * Danilo Stefanovic 2017/0475
    */
    public function back_to_lobby($idLobby,$rating){
       $lobbyModel=new LobbyModel();
       $lobby=$lobbyModel->find($idLobby);
       if($rating!=0)$this->rate_deck($lobby->idDeck,$rating);
       if($lobby->inGame==1){
            $data=[
                'idDeck'=>$lobby->idDeck,
                'idUser'=>$lobby->idUser,
                'maxPlayers'=>$lobby->maxPlayers,
                'PlayerList'=>$lobby->PlayerList,
                'lobbyName'=>$lobby->lobbyName,
                'password'=>$lobby->password,
                'status'=>$lobby->status,
                'inGame'=>0
            ];
            $lobbyModel->update($idLobby,$data);
       }
       return $this->lobby($idLobby);
    }
    /**
    * Azuriranje statusa igre za prikaz lobby
    *
    * @param integer $idLobby idLobby
    * 
    * @return json 
    */
    public function update_game($idLobby){
        $lobbyModel=new LobbyModel();
        $lobby=$lobbyModel->find($idLobby);
        if($lobby->inGame==1) return json_encode("yes");
        else return json_encode("no");

    }

    /** ocenjivanje spila
    * @return void
    * @param integer $idDeck idDeck
    * @param double $Rating Rating
    */
    public function rate_deck($idDeck, $Rating)
    {
        $dModel = new DeckModel();
        $deck = $dModel->find($idDeck);
        $numberOfRatings = $deck->numberOfRatings + 1;
        $prevRating = $deck->Rating;
        $newRating = $prevRating*($numberOfRatings - 1) + $Rating;
        $newRating = $newRating/$numberOfRatings;
        
        $dModel->query("UPDATE deck SET Rating = $newRating, numberOfRatings = $numberOfRatings WHERE idDeck = $idDeck;");
    }
    //--------------------------------------------------------------------
    
    //-------------GAME RELATED-------------------------------------------
    /** uzimanje karata iz spila/od drugih korisnika 
     * @return $cards
    */
    public function draw( $idUserThrown, $idUserAffected, $numOfCards, $idSource, $idLobby)
    {
        // update poteza koji trenutno $idUserThrown igra da bi svi znali sta se radi u igri (ali ne vide koje se karte vuku itd)
        $update = "draw";
        $update = $update.",".$idUserThrown.",".$idUserAffected.",".$numOfCards.",".$idSource.";";
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

    /**  stavlja u update da neko mora da vuce do neke karte 
    * @return void
    *
    * @param integer $idUserThrown idUserThrown
    * @param integer $idUserAffected idUserAffected
    * @param string $card card
    * @param string $matchCard matchCard
    * @param integer $idLobby idLobby
    */
    public function drawUntil($idUserThrown, $idUserAffected, $card, $matchCard, $idLobby)
    {
        $update = "drawUntil".",".$idUserThrown.",".$idUserAffected.",".$card.",".$matchCard.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

    /**  stavlja u update da neko mora da vuce do neke karte 
    * @return json_encode("topcina")
    * @param integer $idUserThrown idUserThrown
    * @param integer $idUserAffected idUserAffected
    * @param integer $idLobby idLobby
    */
    public function skip($idUserThrown, $idUserAffected, $idLobby)
    {
        $update = "skip";
        $update = $update.",".$idUserThrown.",".$idUserAffected.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
        return json_encode("topcina");
        // ideja je da ce korisnik koji treba da bude preskocen da vidi da treba da bude preskocen 
        // i kada dodje red na njega on samo moze da zavrsi potez
    }

    /** vraca karte sa vrha spila ili iz ruka drugih korisnika 
    * @return cards
    * @param integer $idUserThrown idUserThrown
    * @param integer $idSource idSource
    * @param integer $num num
    * @param integer $idLobby idLobby
    */
    public function viewCard($idUserThrown, $idSource, $num, $idLobby)
    {
        $update = "view";
        $update = $update.",".$idUserThrown.",".$idSource.",".$num.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update); // klasican update

        $userHandModel = new UserHandModel();
        $cardsToView = $userHandModel->getXCards($idSource, $num);
        return json_encode($cardsToView);
    }

    /** vraca sve sto se dogodilo u trenutnom potezu
    *  @return update
    * @param integer $idLobby idLobby
    */
    public function update($idLobby)
    {
        return json_encode((new GameUpdateModel())->getUpdate($idLobby));
    }

    /**  vraca ruku od korisnika koji je pozvao 
    * @return cards
    * @param integer $idUser idUser
    */
    public function myHand($idUser)
    {
        return json_encode((new UserHandModel())->getUserHand($idUser));
    }

    /**  postavlja potez kao zavrsen u apdejtu 
    * @return void
    * @param integer $idLobby idLobby
    */
    public function endTurn($idLobby)
    {
        $update = "endTurn".";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

    /** postavlja trenutni potez na nekog korisnika ako uspe i azurira update 
    * @return isClaimed
    * @param integer $idUser idUser
    * @param integer $idLobby idLobby
    * @param string $cardThrown cardThrown
    */
    public function claimTurn($idUser, $idLobby, $cardThrown)
    {
        $gum = new GameUpdateModel();
        $update = $gum->getUpdate($idLobby);
        $pos = strpos($update, "endTurn");
        if($pos === false) return json_encode(false);
        else 
        {
            $update = "claimed,".$idUser.";";
            $gum->newUserUpdate($idUser, $update, $idLobby);
        }
        return json_encode(true);
    }

    /**  menja globalno pravilo i azurira update 
    * @return void
    * @param string $rule rule
    * @param string $newValue newValue
    * @param integer $idLobby idLobby
    */
    public function changeGlobalRule( $rule, $newValue, $idLobby)
    {
        $update = "cgr,".$rule.",".$newValue.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

    /** baca kartu na talon i azurira update 
    * @return void
    * @param integer $idUser idUser
    * @param string $card card
    * @param integer $idLobby idLobby
    */
    public function throw($idUser, $card, $idLobby)
    {
        $userHandModel = new UserHandModel();
        $userHandModel->takeSpecificCard($idUserThrown, $card);
        $update = "throw,".$idUser.",".$card.";";
        (new GameUpdateModel)->addToUpdate($idLobby, $update);
    }

}