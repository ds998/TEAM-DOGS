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

    public function addAdmin($deck_id){

        $adminModel = new AdminModel();

        $response = $adminModel->addAdmin($newAdmin_id);

        if($response == -1)return -1;

        return redirect()->to(site_url('Controller')); 
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