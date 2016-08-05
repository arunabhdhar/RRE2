<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userfriend;
use App\Models\Userblockedfriend;
use App\Models\Userchat;
use App\Models\Country;
use App\Models\City;
use Illuminate\Http\Request;
use Validator;
use Input;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

class GuestuserController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    
     /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
    }
	
    public function getIndex() {
        //die(0);        
    }
    
   
	
    public function getProfile($id)
    {
        $friend_status_msg = "";
        $guest_id = $id;
        $user_id = Auth::user()->user_id;
        $all_friend_list = array();
        
        $all_user = User::getRecentUser();
        $all_friend = Userfriend::getFriendList($user_id);
        
        if($all_friend){             
            $all_friend_list = $all_friend;
        }
        
        $user_geust_details = User::getUserProfileDetails($id);
        if($user_geust_details){             
            $user_guest_all_details = $user_geust_details[0];
        } 
        
        $user_details = User::getUserProfileDetails($user_id);
        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        $user_blocked_data = Userblockedfriend::checkBlockedData($user_id, $guest_id);
        if( $user_blocked_data ){             
            $user_blocked_status = 1;
        } else {
            $user_blocked_status = 0;
        }
        
        
        $check_already_friend = Userfriend::checkAlreadyFriend($user_id, $guest_id);
        
        if($check_already_friend){
            $fetchDataObj = $check_already_friend[0];

            $friend_status = $fetchDataObj->friend_request_status;
            if( $friend_status == 0 )
            {
                if( $fetchDataObj->user_id == $user_id && $fetchDataObj->friend_id == $guest_id )
                {
                    $friend_status_msg = "Request pending";
                } 
                else if( $fetchDataObj->user_id == $guest_id && $fetchDataObj->friend_id == $user_id )
                {
                    $friend_status_msg = "Accept request";
                }
            }
            else if( $friend_status == 1 )
            {
                $friend_status_msg = "Friend";
            }
            else if( $friend_status == 3 )
            {
                $friend_status_msg = "Blocked";
            }
        }

         
        return view('guestuser.profile', ['user' => $user_guest_all_details, 'recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'user_details' => $user_all_details, 'user_blocked_status' => $user_blocked_status])->with('Friend_Status', $friend_status_msg);
    }
}
