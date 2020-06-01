<?php namespace App\Controllers;
/**
* Controller.php – fajl za opstu Controller klasu
* Danilo Stefanovic 2017/0475; Maja Dimitrijevic 2017/0723; Uros Ugrinic 2017/0714; Damjan Pavlovic 2017/0312
* Posto su deljeni kontroleri po kategorijama, a svako je imao svoje f-je u klasama Controller i UserController,navedeno je svacije vlasnistvo
* @version 1.0
*/
use App\Models\DeckModel;
use App\Models\UserModel;
use App\Models\UserDeckModel;
/**
* UserController – klasa koja sadrzi funkcije za kategoriju Registrovani Korisnik
* 
* @version 1.0
*/
class UserController extends Controller
{
    public function index($idUser = null){

        if ($idUser != null) {
            $userModel = new UserModel();
            $user = $userModel->find($idUser);
            $this->session->set('user', $user);
        }

        $this->session->set('controller', 'UserController');
        //return $this->show('main', ['controller'=>$this->session->get('controller')]);

    }
    /**
    * Prikazivanje prikaza deljenja spilova
    *
    * @param string @controller controller
    * @param integer @deck_id deck_id
    * @param string @message message
    *
    * @return function show
    */
    public function share_a_deck($deck_id,$message=null){
        $controller = $this->session->get('controller');
        return $this->show('deljenje_spilova',['controller'=>$controller,'deck_id'=>$deck_id,'message'=>$message]);
    }

    public function save_user_deck($idUser, $idDeck)
    {
        $udModel = new UserDeckModel();
        $dModel = new DeckModel();
        $creatorId = $dModel->find($idDeck)->idUser;

        $data = [
            'idUser' => $idUser,
            'idDeck' => $idDeck,
            'idCreator' => $creatorId,
            'Rating' => 5
        ];

        $udModel->insert($data);
    }

    public function decklab()
    {
        
        echo "deckDesc =".$this->request->getVar('deckDecription');
        if($this->request->getVar('deckDecription')){
            $desc  = $this->request->getVar('deckDecription');
            $cards = $this->request->getVar('cards');
            $suits = $this->request->getVar('suits');
            $name  = $this->request->getVar('deckName');
            $rules = $this->request->getVar('rules');
            $globalRules = $this->request->getVar('globalRules');

            $deckModel = new DeckModel();
            $data = [
                'idUser' => 1,
                'cardRules' => $rules,
                'Cards' => $cards,
                'descr' => $desc,
                'name' => $name,
                'globalRules' => $globalRules,
                'suits' => $suits,
                'Rating' => 0,
                'numberOfPlays' => 0,
                'numberOfRatings' => 0,
            ];
            $deckModel->insert($data);
            return redirect()->to(site_url("controller/index"));
        }
        else return $this->show('decklab',[]);
    }

    
    /**
    * Pokusaj prikljucivanja lobby-u
    *
    * @param integer $deck_id deck_id
    * 
    * @return function share_a_deck or function redirect
    */
    public function share_deck_submit($deck_id){
        if(!$this->validate(['share_textbox'=>'required'])){
            return $this->share_a_deck($this->session->get('controller'),$deck_id,'Textbox is empty.&nbsp;&nbsp;&nbsp;');
        }
        $userModel=new UserModel();
        $s_user=$userModel->findName($this->request->getVar('share_textbox'));
        if($s_user==null){
            return $this->share_a_deck($this->session->get('controller'),$deck_id,'That user does not exist.');
        }
        $deckModel=new DeckModel();
        $creatorId=($deckModel->find($deck_id))->idUser;
        $udModel=new UserDeckModel();
        $udVar=$udModel->getEntry($s_user[0]->idUser,$deck_id);//username treba da bude unique
        if($udVar!=null){
            return $this->share_a_deck($this->session->get('controller'),$deck_id,'That user already has this deck.');
        }
        $data = [
            'idUser' => $s_user[0]->idUser,
            'idDeck' => $deck_id,
            'idCreator' => $creatorId,
            'Rating' => 0
        ];
        $udModel->insert($data);
        return redirect()->to(site_url('Controller'));

        
    }



	
	//--------------------------------------------------------------------

}