<?php 
namespace App\Controllers;
use App\Models\AdminModel;
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
    
    public function viewHDecks(){
        $hdecksModel = new HDecksModel();
        return $hdecksModel->findAll();
    }


    public function changeHDeck($newID, $orderNum){
        if($orderNum > 9 || $orderNum < 0){
            return null;
        }
        return (new HDecksModel())->changeHD($newID, $orderNum);
    }
}