<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userfriend;
use App\Models\Userchat;
use App\Models\Interest;
use Illuminate\Http\Request;
use Validator;
use Input;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Pagination\Paginator;

class SearchuserController extends Controller
{
    
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

    }
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getSearcheduser() {
        
        $search_key = Input::get("searchkey");
        $all_searched_user = array();
        $all_friend_list = array();
        
        if( $search_key != "" ){
            $user_id = Auth::user()->user_id;
            $user_details = User::getUserProfileDetails($user_id);
            $all_friend = Userfriend::getFriendList($user_id);
            $all_user = User::getRecentUser();
            
            if($all_friend){            
            
                $all_friend_list = $all_friend;
            }
        
            $all_searched_user = User::getSearchedUser($search_key, $user_id);
            
            if($user_details){             
                $user_all_details = $user_details[0];
            } 
            if($all_searched_user){
                return view('searchuser.searcheduser', ['paginator' => $all_searched_user, 'user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list])->with('search_key', $search_key);
            }
            else{
                return view('searchuser.searcheduser', ['paginator' => $all_searched_user]);
            }
        }
        else{
            return view('searchuser.searcheduser', ['paginator' => $all_searched_user]);
        }
     
    }
    
     
    /**
     * Get search near by all users
     *
     * @param  array  $data
     * @return User
     */
    public function getSearchednearby() {
        
        $user_id = Auth::user()->user_id;
        $user_details = User::getUserProfileDetails($user_id);
        $all_friend = Userfriend::getFriendList($user_id);
        $all_user = User::getRecentUser();
        $all_interest = Interest::getInterestList();
        $all_friend_list = array();
        
        if($all_friend){            

            $all_friend_list = $all_friend;
        }

        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        return view('searchuser.searchednearby', ['user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list, 'interest_list' => $all_interest]);       
        
    }
    
    /**
     * Get search near by all users
     *
     * @param  array  $data
     * @return User
     */
    public function postSearchnearbyres(Request $request) {
        
        $postdata = Input::all();
        
        if( isset($postdata['user_gender']) )
        {
            $user_gender = $postdata['user_gender'];
        } else {
            $user_gender = "";
        }
        
        if( isset($postdata['user_interest']) )
        {
            if( !empty($postdata['user_interest']) )
            {
                $user_interest = implode(",", $postdata['user_interest']);
            } else {
                $user_interest = "";
            }
        } else {
            $user_interest = "";
        }
        
        
        if( isset($postdata['user_interest_all']) )
        {
            $all_interest = Interest::getInterestList();
            foreach( $all_interest as $interestval )
            {
                $user_interest = $interestval->interest_id.",".$user_interest;
            }
        }
        //echo $user_interest;
        //die(0);
        $distance = 100;
        $useripaddress = getRealIP();
        if( $useripaddress != "" || $useripaddress == '127.0.0.1' ){
            $useripaddress = "120.59.12.143";
        }
        //$useripaddress = "120.59.12.143";
        
        $getuserlocation = getUserLocationFromIp($useripaddress);
        
        if( $postdata['hduserlat'] != "" && $postdata['hduserlon'] != "" )
        {
            $user_lat = $postdata['hduserlat'];
            $user_long = $postdata['hduserlon'];
        } 
        else if( $getuserlocation && $useripaddress != '127.0.0.1' )
        {
            $geo = explode(",", $getuserlocation->loc);
            $user_lat = $geo[0];
            $user_long = $geo[1];
        }
        else{
            $user_lat = "";
            $user_long = "";
        }
        
        if( $user_lat != "" && $user_long != "" ){
            $user_latlong['user_lat'] = $user_lat;
            $user_latlong['user_long'] = $user_long;
            $user_latlong['user_gender'] = $user_gender;
            $user_latlong['user_interest'] = $user_interest;
            Session::put('user_latlong_session', $user_latlong); //array index
            
            return redirect()->to('/searchuser/searchednearbyuserlist')->with('flash_message', 'Searched user near by!');
        }
        
    }
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getSearchednearbyuserlist() {
        
        $page_id = Input::get("page");
        $all_searched_user = array();
        $distance = 6000;
        $user_latlong = Session::get('user_latlong_session');
        $user_lat = $user_latlong['user_lat'];
        $user_long = $user_latlong['user_long'];
        $user_gender = $user_latlong['user_gender'];
        $user_interest = $user_latlong['user_interest'];
        
        $all_friend_list = array();
        
        if( $user_lat != "" && $user_long != "" ){
            $user_id = Auth::user()->user_id;
            $user_details = User::getUserProfileDetails($user_id);
            $all_friend = Userfriend::getFriendList($user_id);
            $all_user = User::getRecentUser();
            
            if($all_friend){            
            
                $all_friend_list = $all_friend;
            }
        
            $all_searched_user = User::getSearchedUserNearBy($user_lat, $user_long, $user_id, $distance, 'miles', $page_id, $user_gender, $user_interest);
            
            if($user_details){             
                $user_all_details = $user_details[0];
            } 
            
            if($all_searched_user){
                return view('searchuser.searchednearbyuserlist', ['paginator' => $all_searched_user, 'user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list]);
            }
            else{
                return view('searchuser.searchednearbyuserlist', ['paginator' => $all_searched_user, 'user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list]);
            }
        }
        else{
            return redirect()->to('/search/searchednearby')->with('flash_message', 'Searched user near by!');
        }
     
    }
}
