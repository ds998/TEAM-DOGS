<?php 
namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\HDecksModel;
class AdminController extends UserController
{
    public function index($idUser=null){
        if ($idUser != null) {
            $userModel = new UserModel();
            $user = $userModel->find($idUser);
            $this->session->set('user', $user);
        }

        $this->session->set('controller', 'AdminController');
        return $this->show('main', ['controller'=>$this->session->get('controller')]);
    }

	//--------------------------------------------------------------------

    public function registerAdmin(){
        if($this->request->getVar('userID'))
		{
            $userID=$this->request->getVar('userID');
            $adminModel = new AdminModel();
            $adminModel->registerAdmin($userID, 0);
            $controller=$this->session->get('controller');
            return redirect()->to(site_url("$controller/index"));
        }
        else return $this->show('registeradmin',['controller'=>$this->session->get('controller')]);
    }
    
    public function changeHD()
    {
        $controller=$this->session->get('controller');
        if($this->request->getVar('idDeck'))
        {
            $idDeck = $this->request->getVar('idDeck');
            $seqNum = $this->request->getVar('seq');
            if($seqNum < 9 && $seqNum > 0)
            {
                $hdecksmodel = new HDecksModel();
                $hdecksmodel->change_HD($idDeck, $seqNum);
                
                return redirect()->to(site_url("$controller/index"));
            }
            return $this->show('HD_change',['controller'=>$controller]);
        }
        else 
        {
            return $this->show('HD_change',['controller'=>$controller]);
        }
    }

    public function viewHDecks(){
        $hdecksModel = new HDecksModel();
        return $hdecksModel->findAll();
    }

}