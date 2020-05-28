<?php namespace App\Controllers;
use App\Models\DeckModel;
use App\Models\UserModel;
use App\Models\UserDeckModel;
class UserController extends Controller
{
    public function index(){
        
        $userModel=new UserModel();
        $user=$userModel->find(5);
        $this->session->set('user',$user);
        $this->session->set('controller','UserController');
        //ovaj deo bi trebalo da stoji kod login,id odabran samo radi testiranja
        return $this->all_lobbies();
        //return $this->share_a_deck($this->session->get('controller'),0);
        //za testiranje
    }

    public function share_a_deck($controller,$deck_id,$message=null){
        return $this->show('deljenje_spilova',['controller'=>$controller,'deck_id'=>$deck_id,'message'=>$message]);
    }

    public function register(){
        if($this->request->getVar('username'))
		{
            $username=$this->request->getVar('username');
            $email=$this->request->getVar('email');
            $password=$this->request->getVar('password');
            $userModel = new UserModel();
            $response = $userModel->register($username, $email, password_hash($password, PASSWORD_BCRYPT));
            if($response == -1 || $response == -2)return $this->show('register',[]);
            return redirect()->to(site_url("controller/index"));
        }
        else return $this->show('register',[]);
    }

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