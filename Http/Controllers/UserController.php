<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userfriend;
use App\Models\Userblockedfriend;
use App\Models\Userchat;
use App\Models\Country;
use App\Models\City;
use App\Models\Interest;
use Illuminate\Http\Request;
use Validator;
use Input;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

class UserController extends Controller
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
        $this->user_id = $auth->id();    
        $this->middleware('auth');
    }
    
    public function getIndex() {
        //die(0);
        
    }
    
    public function getHome() {
        $user_all_details = "";
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
        
        $user_details = User::getUserProfileDetails($user_id);
        $all_friend = Userfriend::getFriendList($user_id);
        $all_user = User::getRecentUser();
        $all_interest = Interest::getInterestList();
        
        if($all_friend){            
            
            $all_friend_list = $all_friend;
        }
        
        if($user_details){             
            $user_all_details = $user_details[0];
        }       
        
        return view('user.home', ['user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list, 'interest_list' => $all_interest ]);        
    }
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getProfile() {
        
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
         
        $all_friend = Userfriend::getFriendList($user_id);
        $all_user = User::getRecentUser();
        $user_details = User::getUserProfileDetails($user_id);
        
        $all_country = Country::getCountryList();
        $all_city = City::getCityList();
        
        if($all_friend){            
            
            $all_friend_list = $all_friend;
        }
        
        if($user_details){             
            $user_all_details = $user_details[0];
        }  
        
        return view('user.profile', ['user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list, 'city_list' => $all_city, 'country_list' => $all_country]);
        //die(0);        
    }
    
    /**
     * Get user change password
     *
     * @param  array  $data
     * @return User
     */
    public function getChangepassword() {
        
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
         
        $all_friend = Userfriend::getFriendList($user_id);
        $all_user = User::getRecentUser();
        $user_details = User::getUserProfileDetails($user_id);
        
        $all_country = Country::getCountryList();
        $all_city = City::getCityList();
        
        if($all_friend){            
            
            $all_friend_list = $all_friend;
        }
        
        if($user_details){             
            $user_all_details = $user_details[0];
        }  
        
        return view('user.changepassword', ['user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list]);
        //die(0);        
    }
    
    /**
     * Get user change password
     *
     * @param  array  $data
     * @return User
     */
    public function postPasswordupdate(Request $request) {
        
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'user_password' => 'required|confirmed',
        ]);
        
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $id  = $this->user_id;
        
            $user = User::findOrFail($id);
            $postdataarr = array(
                                'user_password' => bcrypt($postdata['user_password'])
                            );
            // print_r($postdataarr);
            // die();
            $user->update($postdataarr);
            return redirect()->back()->with('flash_message', 'Password updated successfully!');
        }
        //die(0);        
    }
    
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function postUpdateprofilecover(Request $request) {
        
        $postdataarr = array();
        $userPicUrl = "";
        $id  = $this->user_id;
        
        $user = User::findOrFail($id);
        $postdata = Input::all();
        
        // getting all of the post data
        $user_profile_cover = array('image' => Input::file('user_profile_cover'));
        // setting up rules
        $rules = array('image' => 'required'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($user_profile_cover, $rules);
        
        if (Input::hasFile('user_profile_cover')) {
            if( $validator->fails() )
            {
                // send back to the page with the input data and errors
                //return Redirect::to('profile')->withInput()->withErrors($validator);
                return redirect()->back()->withInput()->withErrors($validator);
            }
            
        }
        
        if ( Input::hasFile('user_profile_cover') )
        {
            if (Input::file('user_profile_cover')->isValid()) {
                $file_tmp = $_FILES['user_profile_cover']['tmp_name'];
                $file_name = $_FILES['user_profile_cover']['name'];
                $userPicUrl = uploadfile($file_tmp, $file_name, 'profilecover', 'images');
                // sending back with message
            }
        } else {
            $userPicUrl = $postdata['userhdnimage'];
        }
        
        $userObj = new User;
        
        $postdataarr = array(
                            'user_profile_cover' => $userPicUrl,
                        );
        // print_r($postdataarr);
        // die();
        $userObj->updateProfileCover($id, $postdataarr);

        return redirect()->back()->with('flash_message', 'Profile cover updated successfully!');
    }
    
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function postUpdate(Request $request) {
        
        $postdataarr = array();
        $userPicUrl = "";
        $id  = $this->user_id;
        
        $user = User::findOrFail($id);
        $postdata = Input::all();
        
        // getting all of the post data
        $user_image = array('image' => Input::file('user_image'));
        // setting up rules
        $rules = array('image' => 'required'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($user_image, $rules);
        
        if (Input::hasFile('user_image')) {
            if( $validator->fails() )
            {
                // send back to the page with the input data and errors
                //return Redirect::to('profile')->withInput()->withErrors($validator);
                return redirect()->back()->withInput()->withErrors($validator);
            }
            
        }
        
            if ( Input::hasFile('user_image') )
            {
                if (Input::file('user_image')->isValid()) {
                    $file_tmp = $_FILES['user_image']['tmp_name'];
                    $file_name = $_FILES['user_image']['name'];
                    $userPicUrl = uploadfile($file_tmp, $file_name, 'profile', 'images');
                    // sending back with message
                }
            } else {
                $userPicUrl = $postdata['userhdnimage'];
            }
            
            if( $postdata['user_dob_month'] != "" && $postdata['user_dob_day'] != "" && $postdata['user_dob_year'] != "" )
            {
                //setDateFormateDashed($data['user_dob'], "DD-MM-YYYY")
                $user_dob = $postdata['user_dob_year']."-".$postdata['user_dob_month']."-".$postdata['user_dob_day'];
            } else {
                $user_dob = "";
            }
            //uploadfile($file_tmp, $file_name, 'appimage/appicon', 'images', 100, 100);
            $postdataarr = array(
                                'user_full_name' => $postdata['user_full_name'],
                                'user_dob' => $user_dob,
                                'user_city' => $postdata['user_city'],
                                'user_country' => $postdata['user_country'],
                                'user_about' => $postdata['user_about'],
                                'user_profile_screen_status' => $postdata['user_profile_screen_status'],
                                'user_image' => $userPicUrl,
                                'user_relationship_status' => $postdata['user_relationship_status'],
                                'user_gender' => $postdata['user_gender']
                            );
            // print_r($postdataarr);
            // die();
            $user->update($postdataarr);

            return redirect()->back()->with('flash_message', 'Profile updated successfully!');
    }
    
    /**
     * Get user setting
     *
     * @param  array  $data
     * @return User
     */
    public function getSetting() {
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
        $all_user = User::getRecentUser();
        $all_friend = Userfriend::getFriendList($user_id);
        
        if($all_friend){             
            $all_friend_list = $all_friend;
        }
        
        $user_details = User::getUserProfileDetails($user_id);
        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        return view('user.setting', ['recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'user_details' => $user_all_details]);
        //die(0);        
    }
    
    
    public function getNotification() {
        
        
        $pending_friend_list = array();
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
        $all_friend = Userfriend::getFriendList($user_id);
        $pending_friend = Userfriend::getPendingFriendRequest($user_id);
        
        $update_notify_friend = Userfriend::updateNotifyPendingFrndReq($user_id);
        
        $pending_notify_msg = Userchat::getUnreadMessageNotifyList($user_id);
        
        $all_user = User::getRecentUser();        
        
        if($pending_friend){            
            
            $pending_friend_list = $pending_friend;
        }
        
        if($all_friend){            
            
            $all_friend_list = $all_friend;
        }
        $user_details = User::getUserProfileDetails($user_id);
        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        return view('user.notification', ['user_notification' => $pending_friend_list, 'recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'user_details' => $user_all_details, 'pending_notify_msg' => $pending_notify_msg]);
        //die(0);        
    }
    
    public function getInbox() {
        
        
        $pending_friend_list = array();
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
        $all_friend = Userfriend::getFriendList($user_id);
        $pending_friend = Userfriend::getPendingFriendRequest($user_id);
        $pending_notify_msg = Userchat::getUnreadMessageNotifyList($user_id);
        
        $update_notify_msg = Userchat::updateUnreadMessageNotify($user_id);
        
        //echo "<pre>";
        //print_r($pending_notify_msg);
        
        $all_user = User::getRecentUser();        
        
        if($pending_friend){            
            
            $pending_friend_list = $pending_friend;
        }
        
        if($all_friend){            
            
            $all_friend_list = $all_friend;
        }
        $user_details = User::getUserProfileDetails($user_id);
        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        return view('user.inbox', ['user_notification' => $pending_friend_list, 'recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'user_details' => $user_all_details, 'pending_notify_msg' => $pending_notify_msg]);
        //die(0);        
    }
    
    
    public function getFriendrequest() {
        
        $guest_id = Input::get("guestid");
        $friend_request_status = Input::get("frnd_request_status");
        $friend_block = Input::get("friend_block");
        $connect_message = Input::get("connect_message");
        $user_id = Auth::user()->user_id;
        
        if( $user_id == "" )
        {
            echo "Login-Please";           
        }
        else if( $user_id !="" && $guest_id != "" && $friend_request_status != "" )
        {
            if( $friend_request_status == 1 )
            {
                $check_already_friend = Userfriend::checkAlreadyFriend($user_id, $guest_id);
                if($check_already_friend){
                    $fetchDataObj = $check_already_friend[0];

                    $friend_status = $fetchDataObj->friend_request_status;
                    $friend_block_status = $fetchDataObj->friend_block_status;
                    if( $friend_status == 0 )
                    {
                        echo "Request-Pending";
                    } else if( $friend_status == 1 ) {
                        echo "Already-Friend";
                    } else if( $friend_status == 2 && $friend_block_status == 0 ) {
                        $userFriendObj = new Userfriend;

                        $postdataarr = array(
                                        'user_id' => $user_id,
                                        'friend_id' => $guest_id,
                                        'friend_request_status' => 0
                                    );
                        $updtRes = $userFriendObj->updateFriendStatusDataAgain($postdataarr);
                        if( $updtRes ){
                            echo "Friend-Request-Added";
                        } else {
                            echo "Not-Done";
                        }
                    } else if( $friend_status == 3 && $friend_block_status == 0 ) {
                         $userFriendObj = new Userfriend;

                        $postdataarr = array(
                                        'user_id' => $user_id,
                                        'friend_id' => $guest_id,
                                        'friend_request_status' => 0
                                    );
                        $updtRes = $userFriendObj->updateFriendStatusDataAgain($postdataarr);
                        if( $updtRes ){
                            echo "Friend-Request-Added";
                        } else {
                            echo "Not-Done";
                        }
                    } else if( $friend_block_status == 0 ) {
                         echo "Friend-Block";
                    }
                }
                else
                {
                    $userFriendObj = new Userfriend;

                    $postdataarr = array(
                                    'user_id' => $user_id,
                                    'friend_id' => $guest_id,
                                    'friend_request_message' => $connect_message,
                                    'friend_request_status' => 0
                                );

                    $insrtRes = $userFriendObj->insertData($postdataarr);
                    if( $insrtRes ){
                        echo "Friend-Request-Added";
                    } else {
                        echo "Not-Done";
                    }
                }
            } else if( $friend_request_status == 2 ) {

                $userFriendObj = new Userfriend;

                $postdataarr = array(
                                'user_id' => $user_id,
                                'friend_id' => $guest_id
                            );

                $delRes = $userFriendObj->deletePendingFriendStatusData($postdataarr);
                if( $delRes ){
                    echo "Delete-Request";
                } else {
                    echo "Not-Done";
                }
            } else if( $friend_request_status == 3 ) {

                $userFriendObj = new Userfriend;

                $postdataarr = array(
                                'user_id' => $user_id,
                                'friend_id' => $guest_id,
                                'friend_request_status' => 1,
                                'notify_friend_req_status' => 1
                            );

                $updtRes = $userFriendObj->updateFriendStatusData($postdataarr);
                if( $updtRes ){
                    echo "Request-Accepted";
                } else {
                    echo "Not-Done";
                }
            } else if( $friend_request_status == 4 ) {

                $userFriendObj = new Userfriend;

                $postdataarr = array(
                                'user_id' => $user_id,
                                'friend_id' => $guest_id,
                                'friend_request_status' => 2,
                                'notify_friend_req_status' => 1
                            );

                $updtRes = $userFriendObj->updateFriendStatusData($postdataarr);
                if( $updtRes ){
                    echo "Request-Rejected";
                } else {
                    echo "Not-Done";
                }
            } else if( $friend_request_status == 5 ) {

                $userFriendObj = new Userfriend;

                $postdataarr = array(
                                'user_id' => $user_id,
                                'friend_id' => $guest_id,
                                'friend_request_status' => 3,
                                'notify_friend_req_status' => 1
                            );

                $updtRes = $userFriendObj->deleteFriendStatusData($postdataarr);
                if( $updtRes ){
                    echo "Un-Friend";
                } else {
                    echo "Not-Done";
                }
                
            } else if( $friend_request_status == 6 ) {

                $userFriendObj = new Userfriend;
                
                $postdataarr = array(
                                'user_id' => $user_id,
                                'friend_id' => $guest_id,
                                'friend_request_status' => 3,   
                                'friend_block_status' => $friend_block,
                                'notify_friend_req_status' => 1
                            );
                
                $updtRes = $userFriendObj->blockedFriendStatusDataAgain($postdataarr);
                
                if( $updtRes ){                   
                    
                    $userBlockedFriendObj = new Userblockedfriend;

                    $postdataarr = array(
                                    'user_id' => $user_id,
                                    'friend_id' => $guest_id
                                );
                    if( $friend_block == 0 )
                    {
                        $delRes = $userFriendObj->deleteBlockedFriendData($postdataarr);   
                        $updtRes = $userBlockedFriendObj->deleteBlockedData($postdataarr);                        
                    }
                    else if( $friend_block == 1 ) 
                    {
                        $check_already_blocked = $userBlockedFriendObj->checkAlreadyFriendBlocked($postdataarr);
                       
                        if( count($check_already_blocked) <= 1 ){
                            $updtRes = $userBlockedFriendObj->insertData($postdataarr);
                        }
                    }
                    
                    if( $friend_block == 1 )
                    {
                        echo "Friend-Blocked";
                    }
                    else if( $friend_block == 0 )
                    {
                        echo "Friend-UnBlocked";
                    }
                } else {
                    echo "Not-Done";
                }
            }
             
        }
        //die(0);        
    }
    
    /*
     * Author:Jinandra
     * Date: 02-09-015
     * Accept and decline friend request
     */
    
    public function getAcceptdeclinerequest() {
        
        $friend_id = Input::get("friend_id");
        $friend_request_status = Input::get("frnd_request_status");
        $user_id = Auth::user()->user_id;
        
        if( $user_id == "" )
        {
            echo "Login-Please";           
        }
        else if( $user_id !="" && $friend_id != "" && $friend_request_status != "" )
        {
            $check_already_friend = Userfriend::checkFriendRequestStatus($user_id, $friend_id);
            if($check_already_friend){
                $fetchDataObj = $check_already_friend[0];
               
                $friend_status = $fetchDataObj->friend_request_status;
                if( $friend_status == 0 )
                {
                    $userFriendObj = new Userfriend;
                
                    $postdataarr = array(
                                    'user_id' => $user_id,
                                    'friend_id' => $friend_id,
                                    'friend_request_status' => $friend_request_status
                                );

                    $updtRes = $userFriendObj->updateFriendStatusData($postdataarr);
                    if( $updtRes ){
                        echo "Data-Saved";
                    } else {
                        echo "Not-Done";
                    }
                } 
                else if( $friend_status == 1 )
                {
                    echo "Already-Friend";
                }
            }
            else
            {
               echo "No friend request available";
            }
        }
        //die(0);        
    }
    
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getChat($unid) {
        
        $all_friend_list = array();
        $user_chat_list = array();
        $user_all_details = array();
        $friend_id = decryptedUniqueId( $unid );
        
        if( is_int($friend_id) )
        {
            $user_id = Auth::user()->user_id;
            
            $check_already_friend = Userfriend::checkAlreadyFriend($user_id, $friend_id);
            if($check_already_friend){
                $fetchDataObj = $check_already_friend[0];
               
                $friend_status = $fetchDataObj->friend_request_status;
                if( $friend_status == 1 )
                {
                    $all_friend = Userfriend::getFriendList($user_id);
                    $all_user = User::getRecentUser();        

                    $user_details = User::getUserProfileDetails($friend_id);

                    $user_chat = Userchat::getChatMessageList($user_id, $friend_id);
                    //echo "<pre>";
                   // echo get_last_query();
                   // print_r($user_chat);
                   // die(0);
                    if( $user_chat ){
                        $user_chat_list = $user_chat;               
                    }
                    if($user_details){             
                        $user_all_details = $user_details[0];
                    }      

                    if($all_friend){            

                        $all_friend_list = $all_friend;
                    }         

                    $user_details_2 = User::getUserProfileDetails($user_id);
                    if($user_details_2){             
                        $user_all_details_2 = $user_details_2[0];
                    } 

                    return view('user.chat', ['user' => $user_all_details, 'recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'selected_frnd_id' => $friend_id, 'user_chat_list' => $user_chat_list, 'user_details' => $user_all_details_2]);
                    
                } else if( $friend_status == 0 ) {
                    return redirect('guestuser/profile/'.$friend_id)->with('flash_message', 'Sorry, your friend request is pending!');
                } else if( $friend_status == 2 ) {
                    return redirect('guestuser/profile/'.$friend_id)->with('flash_message', 'Sorry, your friend request is rejected!');
                }
            }
            else{
               
                return redirect('guestuser/profile/'.$friend_id)->with('flash_message', 'Sorry, you are not friend!');
            }
            
        
       }       
        //die(0);        
    }
    
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getChatlist() {
        
        $user_chat_list = array();
        $friend_id = Input::get("guestid");
        
        if( $friend_id )
        {
            $user_id = Auth::user()->user_id;       
            
            $user_chat = Userchat::getChatMessageList($user_id, $friend_id);
            //echo "<pre>";
           // echo get_last_query();
           // print_r($user_chat);
           // die(0);
            if( $user_chat ){
                $user_chat_list = $user_chat;               
            }
            
            //$html = View::make('countries.list', ['user_chat_list' => $user_chat_list])->render();
            return (String) view('user.chatlist', ['user_chat_list' => $user_chat_list]);
        
       }       
        //die(0);        
    }
    
    
    /**
     * Post user chat
     *
     * @param  array  $request
     * @return User
     */
    public function postUserchat(Request $request) {
        
        $postdata = Input::all();
                
        if ( isset($postdata['send_message']) ) {
            
            $validator = Validator::make($postdata, [
                                'chat_message' => 'required|max:100',
                            ]);
             
            if( $validator->fails() )
            {
                // send back to the page with the input data and errors
                //return Redirect::to('profile')->withInput()->withErrors($validator);
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else
            {
                $userChatObj = new Userchat;
                
                $send_user_id = Auth::user()->user_id;
                $recv_user_id = $postdata['hdnfrndid'];
                
                if( $recv_user_id )
                {
                    $postdataarr = array(
                                    'send_user_id' => $send_user_id,
                                    'recv_user_id' => $recv_user_id,
                                    'chat_message' => $postdata['chat_message'],
                                    'message_read_status' => 0                    
                                );             
                    
                    $insrtRes = $userChatObj->insertData($postdataarr);

                    if( $insrtRes ){
                        return redirect()->back()->with('flash_message', 'Message send successfully!');
                    } else {
                        return redirect()->back()->with('flash_message', 'Data not send!');
                    }
                }
                else 
                {
                    return redirect()->back()->with('flash_message', 'User not valid!');
                }
            }
            
        }
        else {
            return redirect()->back()->with('flash_message', 'Please submit some message ehere!');
        }
        
    }
    
    /**
     * Get blocked user list
     *
     * @param  array  $request
     * @return User
     */
    public function getBlockeduser() {
        $user_blocked_list = array();
        $all_friend_list = array();
        $user_id = Auth::user()->user_id;
        
        $user_blocked_list = Userblockedfriend::getBlockedUser($user_id);
        
        $user_details = User::getUserProfileDetails($user_id);
        $all_friend = Userfriend::getFriendList($user_id);
        $all_user = User::getRecentUser();

        if($all_friend){    

            $all_friend_list = $all_friend;
        }

        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        if($user_blocked_list){
            return view('user.blockeduser', ['paginator' => $user_blocked_list, 'user' => User::findOrFail($user_id), 'recent_user' => $all_user, 'user_details' => $user_all_details, 'user_friend_list' => $all_friend_list]);
        }
        else{
            return view('user.blockeduser', ['paginator' => $user_blocked_list]);
        }
            
        
    }
    
    /**
     * Get user setting
     *
     * @param  array  $data
     * @return User
     */
    public function getFriendlist() {
        $all_friend_list = array();
        
        $user_id = Auth::user()->user_id;
        $all_user = User::getRecentUser();
        $all_friend = Userfriend::getFriendList($user_id);
        
        if($all_friend){             
            $all_friend_list = $all_friend;
        }
        
        $user_details = User::getUserProfileDetails($user_id);
        if($user_details){             
            $user_all_details = $user_details[0];
        } 
        
        $all_friend_user_list = Userfriend::getFriendListOnProfile($user_id);
        
        return view('user.friendlist', ['paginator' => $all_friend_user_list, 'recent_user' => $all_user, 'user_friend_list' => $all_friend_list, 'user_details' => $user_all_details]);
        //die(0);        
    }
    
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function postUserinterest(Request $request) {
        
        $postdataarr = array();
        $id  = $this->user_id;
        
        $postdata = Input::all();
        
        $validator = Validator::make($postdata, [
            'user_interest' => 'required',
        ]);
        
        if( $validator->fails() )
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $interest_id = $postdata['user_interest'];
        
        $str_impload = implode(",", $interest_id);
        
        if( $str_impload ){
            $userObj = new User;

            //uploadfile($file_tmp, $file_name, 'appimage/appicon', 'images', 100, 100);
            $postdataarr = array(
                                'user_interest' => $str_impload,
                            );
        
            // print_r($postdataarr);
            // die();
            $userObj->updateUserInterest($id, $postdataarr);
        }
        return redirect()->back()->with('flash_message', 'Interests updated successfully!');
    }
    
    public function showProfile($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
