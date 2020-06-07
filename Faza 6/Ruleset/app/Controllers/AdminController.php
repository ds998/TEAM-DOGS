<?php
/**
* AdminController.php – fajl za AdminController klasu
* Uros Ugrinic 2017/0714; 
* @version 1.0
*/
namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\HDecksModel;
/**
* UserController – klasa koja sadrzi funkcije za kategoriju Administrator
* 
* @version 1.0
*/
class AdminController extends UserController
{
    public function index($idUser=null){
        if ($idUser != null) {
            $userModel = new UserModel();
            $user = $userModel->find($idUser);
            $this->session->set('user', $user);
        }

        $this->session->set('controller', 'AdminController');
        $hdecksModel = new HDecksModel();
        $hdecks = $hdecksModel->query(" select decc.name, decc.iddeck, hd.orderNum
                                        from deck decc, hdecks hd
                                        where hd.idDeck=decc.idDeck");
        $hdecks = $hdecks->getResult();
        return $this->show('main', ['controller'=>$this->session->get('controller'),'hdecks'=>$hdecks]);
    }

	//--------------------------------------------------------------------

    /** postavlja zeljenog korisnika kao admina 
    * @return registerAdminStranica || @return redirectToMainMenu
    */
    public function registerAdmin(){
        if($this->request->getVar('userID'))
		{
            $userID=$this->request->getVar('userID');
            $userModel = new UserModel();
            $exists = $userModel->find($userID);
            if($exists == null) return $this->show('registeradmin',['controller'=>$this->session->get('controller')]);
            $adminModel = new AdminModel();
            $adminModel->registerAdmin($userID, 0);
            $controller=$this->session->get('controller');
            return redirect()->to(site_url("$controller/index"));
        }
        else return $this->show('registeradmin',['controller'=>$this->session->get('controller')]);
    }
    
    /** azurira neki HDeck 
    * @return stranicaZaMenjanjeIstaknutihSpilova || @return redirectToMainMenu 
    */
    public function changeHD()
    {
        $controller=$this->session->get('controller');
        if($this->request->getVar('idDeck'))
        {
            $idDeck = $this->request->getVar('idDeck');
            $seqNum = $this->request->getVar('seq');
            if($seqNum <= 9 && $seqNum > 0)
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

    /** nalazi i vraca istaknute spilove 
    * @return hdecks
    */
    public function viewHDecks(){
        $hdecksModel = new HDecksModel();
        return $hdecksModel->findAll();
    }

}