<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Validator;
use Session;
use Input;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
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
        //echo "ggggg";
        //die();
        if( $adminType == "" )
        {   
            //return redirect('admin/login')->with('flash_errmessage', 'Username and password doesnt match');
            //return Redirect::to('/admin/login')->send(); // redirect back to login page
            //return redirect('admin/login');
                    return Redirect::to('/admin/');
                    die();
            //die(0);
            //return Redirect::to('admin/login')->send(); // redirect back to login page
        } else {
            //return Redirect::route('admin/welcome'); // redirect back to login page
        }
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
        
        $adminSessionData = Session::get('adminDetailSession');
        $adminType = $adminSessionData['adminType'];
        
        if( $adminType == "" )
        {
            return redirect('admin/login');
        } else {
            return redirect('admin/welcome');
        }
    }
    
    public function getWelcome()
    {
        $this->isAuthorized();
        return view('admin.welcome');
    }
    
    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLoginsubmit()
    {
        $postdataarr = array();
        $admindataarr = array();
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'user_name' => 'required',
            'user_password' => 'required',
        ]);
        
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $adminObj = new Admin;
            
            $postdataarr = array(
                                'user_password' => md5($postdata['user_password']),
                                'user_name' => $postdata['user_name']
                            );
            //print_r($postdataarr);
            //die(0);
            $adminData = $adminObj->authAdmindetails($postdataarr);
            
            if( $adminData ){
                $adminData = $adminData[0];
                
                $admindataarr['adminName'] = $adminData->user_name;
                $admindataarr['adminId'] = $adminData->admin_id;
                $admindataarr['adminType'] = 'Admin';
            
                Session::put('adminDetailSession', $admindataarr); //array index
                return redirect('admin/welcome');
            } else {
                return redirect()->back()->with('flash_errmessage', 'Username and password doesnt match');
            }            
        }
    }
    
    
    public function getLogout()
    {
        Session::flush();
        return view('admin.login');
    }
    
    public function getChangepassword()
    {
        $this->isAuthorized();
         
        return view('admin.changepassword');
    }
    
    public function postChangepasswordsubmit()
    {
        $this->isAuthorized();
        $postdataarr = array();
        $postdataarr1 = array();
        $admindataarr = array();
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $adminObj = new Admin;
            
            $postdataarr = array(
                                'user_password' => md5($postdata['old_password']),
                            );
            
            $adminCheckPassData = $adminObj->checkOldPassword($postdataarr);
            
            if( $adminCheckPassData ){
                
                $adminSessionData = Session::get('adminDetailSession');
                
                $adminId = $adminSessionData['adminId'];
                
                $postdataarr1 = array(
                                'user_password' => md5($postdata['new_password']),
                                'admin_id' => $adminId,
                            );
                
                $updatePassData = $adminObj->updateAdminPassword($postdataarr1);
                
                if( $updatePassData )
                {
                    return redirect()->back()->with('flash_successmessage', "Password changed successfully");
                } else {
                    return redirect()->back()->with('flash_errmessage', "Sorry, password doesn't changed");
                }
                
            } else {
                return redirect()->back()->with('flash_errmessage', "Old password doesn't match");
            }
            
        }
        
    }
    
    
}
