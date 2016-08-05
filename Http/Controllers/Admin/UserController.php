<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Validator;
use Session;
use Input;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Admin Login 
     *
     * @param  array  $data
     * @return User
     */
    
    
            
    public function __construct()
    {
        $adminSessionData = Session::get('adminDetailSession');
        $adminType = $adminSessionData['adminType'];
        
        $this->isAuthorized();
    }
    
    private function isAuthorized() {
        
        $adminSessionData = Session::get('adminDetailSession');
        $adminType = $adminSessionData['adminType'];
        
        if( $adminType == "" )
        {
            return Redirect::to('/admin/login')->send();
            //return redirect('admin/login');
        } else {
            return Redirect::to('admin/welcome');            
        }
    }
    
    public function getIndex()
    {
        $this->isAuthorized();
        
        $userDataList = array();
        
        $userObj = new User;
        
        $userDataList = User::getAllUser();
        
        if($userDataList){
            return view('admin.user.user', ['paginator' => $userDataList]);
        }
        else{
            return view('admin.user.user', ['paginator' => $userDataList]);
        }
    }
    
}
