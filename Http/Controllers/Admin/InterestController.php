<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Interest;
use Validator;
use Session;
use Input;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterestController extends Controller
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
        $interestDataList = array();
        
        $interestObj = new Interest;
        
        $interestDataList = Interest::getInterestList();
        
        if($interestDataList){
            return view('admin.interest.interest', ['paginator' => $interestDataList]);
        }
        else{
            return view('admin.interest.interest', ['paginator' => $interestDataList]);
        }
    }
    
    public function getAdd()
    {
        $this->isAuthorized();
        return view('admin.interest.add');
        
    }
    
    public function getEdit()
    {
        $this->isAuthorized();
        $interestdata = array();
        
        $interest_id = $_REQUEST['intrestid'];
        
        if( $interest_id != "" )
        {
            $interestObj = new Interest;
            
            $interestdata = $interestObj->findInterest($interest_id);
            
            if( $interestdata )
            {
                $interestData = $interestdata[0];
                
                return view('admin.interest.edit', ['interest' => $interestData]);
            } else {
                return redirect('/admin/interest')->with('flash_errmessage', "Interest not found");
            }
        } else {
            return redirect('/admin/interest')->with('flash_errmessage', "Please select interest first");
        }
        
    }
    
    public function getDeleteinterest()
    {
        $this->isAuthorized();
        
        $interest_id = $_REQUEST['intrestid'];
        
        if( $interest_id != "" )
        {
            $interestObj = new Interest;
            
            $interestdata = $interestObj->findInterest($interest_id);
            
            if( $interestdata )
            {
                $delinterest = $interestObj->deleteInterestData($interest_id);
                return redirect('/admin/interest')->with('flash_successmessage', "Interest deleted successfully");
                
            } else {
                return redirect('/admin/interest')->with('flash_errmessage', "Interest not found");
            }
        } else {
            return redirect('/admin/interest')->with('flash_errmessage', "Please select interest first");
        }
        
    }
    
    public function postAddinterest()
    {
        $this->isAuthorized();
        
        $postdataarr = array();
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'interest_name' => 'required'
        ]);
        
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $interestObj = new Interest;
            
            $interestDupCheckData = $interestObj->findInterestNameCheck($postdata['interest_name']);
            
            if( $interestDupCheckData == false )
            {
                $postdataarr = array(
                                    'interest_name' => $postdata['interest_name'],
                                    'status' => $postdata['status']
                                );

                //print_r($postdataarr);
                //die(0);
                $interestDataRes = $interestObj->insertInterestData($postdataarr);

                if( $interestDataRes )
                {
                    return redirect('/admin/interest')->with('flash_successmessage', "Interest added successfully");
                } else {
                    return redirect('/admin/interest/add')->with('flash_errmessage', "Sorry, Interest not added");
                }
            }else {
                return redirect()->back()->with('flash_errmessage', "Sorry, Interest already available");
            }
        }
        
    }
    
    
    public function postEditinterest()
    {
        $this->isAuthorized();
        
        $postdataarr = array();
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'interest_name' => 'required'
        ]);
        
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $interestObj = new Interest;
            
            $interestDupCheckData = $interestObj->findInterestName($postdata['hdintrestid'], $postdata['interest_name']);
            
            if( $interestDupCheckData == false )
            {
                $postdataarr = array(
                                    'interest_name' => $postdata['interest_name'],
                                    'status' => $postdata['status']
                                );

                //print_r($postdataarr);
                //die(0);
                $interestDataRes = $interestObj->updateInterestData($postdata['hdintrestid'], $postdataarr);

                if( $interestDataRes )
                {
                    return redirect('/admin/interest')->with('flash_successmessage', "Interest updated successfully");
                } else {
                    return redirect()->back()->with('flash_errmessage', "Sorry, Interest not edited");
                }
            }else {
                return redirect()->back()->with('flash_errmessage', "Sorry, Interest already available");
            }
        }
        
    }
    
}
