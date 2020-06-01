<?php 
namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\HDecksModel;
class AdminController extends Controller
{
    public function index(){
        $adminModel = new AdminModel();
        $user = $this->session->get('user');
    }

	//--------------------------------------------------------------------

    public function registerAdmin(){
        if($this->request->getVar('userID'))
		{
            $userID=$this->request->getVar('userID');
            $adminModel = new AdminModel();
            $adminModel->registerAdmin($userID, 0);
            return redirect()->to(site_url("controller/index"));
        }
        else return $this->show('registeradmin',[]);
    }
    
    public function changeHD()
    {
        if($this->request->getVar('idDeck'))
        {
            $idDeck = $this->request->getVar('idDeck');
            $seqNum = $this->request->getVar('seq');
            if($seqNum < 9 && $seqNum > 0)
            {
                $hdecksmodel = new HDecksModel();
                $hdecksmodel->change_HD($idDeck, $seqNum);
                return redirect()->to(site_url("controller/index"));
            }
            return $this->show('HD_change',[]);
        }
        else 
        {
            return $this->show('HD_change',[]);
        }
    }

    public function viewHDecks(){
        $hdecksModel = new HDecksModel();
        return $hdecksModel->findAll();
    }

}