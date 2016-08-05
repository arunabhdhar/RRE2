<?php
namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Userimage;
use App\Models\Userfriend;
use App\Models\Userblockedfriend;
use App\Models\Country;
use App\Models\City;
use App\Models\Interest;
use Validator;
use Session;
use Input;
use Auth;
use Mail;
use Hash;
use Redirect;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Transformers\SchoolTransformer;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        echo "I'm in index";
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function userLogin(Request $request)
    {
        //
        $userDetailsData = array();
        $userDetails = array();
        $extraParams = array();
        $all_interest = array();
        $postdata = Input::all();
        
        $user_email  = isset($postdata['user_email']) ? $postdata['user_email'] : '';
        $user_password  = isset($postdata['user_password']) ? $postdata['user_password'] : '';
        $user_device_type  = isset($postdata['user_device_type']) ? $postdata['user_device_type'] : '';
        $user_device_token  = isset($postdata['user_device_token']) ? $postdata['user_device_token'] : '';
        
        if( $user_email == "" or $user_password == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {
            
            $userObj = new User;
            
            if (Auth::attempt(array('user_email' => $user_email, 'user_password' => $user_password)))
            {
                
                if( Auth::user()->status == 1 )
                {
                    $user_access_token = md5(time().Auth::user()->user_id);
                    
                    $postdataarr = array(
                            'user_id' => Auth::user()->user_id,
                            'user_access_token' => $user_access_token,
                            'user_device_type' => $user_device_type,
                            'user_device_token' => $user_device_token
                        );
                    
                    $userObj->updateUserAccessToken(Auth::user()->user_id, $postdataarr);
                    
                    $responseStr = "User login successfully";
                    
                    $userDetails = Auth::user();
                    
                    $userDetails = User::getUserProfileDetails($userDetails->user_id);
                    $userDetails = $userDetails[0];
                    
                    if( $userDetails->user_interest != "" )
                    {
                        $user_interest_arr = explode(",", $userDetails->user_interest);
                        //print_r($user_interest_arr);
                        $all_interest = Interest::getUserInterest($user_interest_arr);
                    }
                    $i = 0;
                    foreach( $userDetails as $keyname=>$userDetailsVal )
                    {
                        $userDetailsData[$keyname] = $userDetailsVal;
                        $i++;
                    }
                    
                    $userDetailsData['user_access_token'] = $user_access_token;
                    //print_r($all_interest);
                    $userDetailsData['all_interest'] = $all_interest;
                    
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetailsData, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                } else {
                    $userDetails = array();
                    $responseStr = "Sorry, you are blocked by admin";
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                }
	    } else {
                $responseStr = "Email and password doesn't match";
                
                $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
            
        }
    }

    /**
     * User registration
     *
     * @param  Request  $request
     * @return Response
     */
    public function userRegistration(Request $request)
    {
        //
        $userPicUrl = "";
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_email  = isset($postdata['user_email']) ? $postdata['user_email'] : '';
        $user_password  = isset($postdata['user_password']) ? $postdata['user_password'] : '';
        $user_city  = isset($postdata['user_city']) ? $postdata['user_city'] : '';
        $hduserlat  = isset($postdata['hduserlat']) ? $postdata['hduserlat'] : '';
        $hduserlon  = isset($postdata['hduserlon']) ? $postdata['hduserlon'] : '';
        $user_dob_month  = isset($postdata['user_dob_month']) ? $postdata['user_dob_month'] : '';
        $user_dob_day  = isset($postdata['user_dob_day']) ? $postdata['user_dob_day'] : '';
        $user_dob_year  = isset($postdata['user_dob_year']) ? $postdata['user_dob_year'] : '';
        $user_full_name  = isset($postdata['user_full_name']) ? $postdata['user_full_name'] : '';
        $user_profile_screen_status  = isset($postdata['user_profile_screen_status']) ? $postdata['user_profile_screen_status'] : '';
        $user_country  = isset($postdata['user_country']) ? $postdata['user_country'] : '';
        $user_about  = isset($postdata['user_about']) ? $postdata['user_about'] : '';
        $security_question  = isset($postdata['security_question']) ? $postdata['security_question'] : '';
        $security_answer  = isset($postdata['security_answer']) ? $postdata['security_answer'] : '';
        $user_gender  = isset($postdata['user_gender']) ? $postdata['user_gender'] : '';
        $user_relationship_status  = isset($postdata['user_relationship_status']) ? $postdata['user_relationship_status'] : '';
        $user_interest  = isset($postdata['user_interest']) ? $postdata['user_interest'] : '';
        $user_device_type  = isset($postdata['user_device_type']) ? $postdata['user_device_type'] : '';
        $user_device_token  = isset($postdata['user_device_token']) ? $postdata['user_device_token'] : '';
        
        
        if( $user_password == "" or $user_email == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {
            
            $userObj = new User;
            
            $userEmailCheck = $userObj->getUserEmailCheck($user_email);
            
            if( $userEmailCheck ){
                $responseStr = "Sorry, this email already exists";
                
                $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
            /*
            echo "<pre>";
            print_r($postdata);
            die(0); */
            $user_city_other = "";
            // getting all of the post data
            $user_image = array('image' => Input::file('user_image'));
            // setting up rules
            $rules = array('image' => 'required'); //mimes:jpeg,bmp,png and for max size max:10000
            // doing the validation, passing post data, rules and the messages
            $validator = Validator::make($user_image, $rules);

            if (Input::hasFile('user_image')) {
                if( $validator->fails() )
                {
                    $responseStr = "Please upload image";

                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
            }

            $useripaddress = getRealIP();

            if( $user_city == "" || $user_city == 0 )
            {
                $user_city_other = "Other";
            }

            $getuserlocation = getUserLocationFromIp($useripaddress);

            if( $hduserlat != "" && $hduserlon != "" )
            {
                $user_lat = $hduserlat;
                $user_long = $hduserlon;
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

            if( $user_dob_month != "" && $user_dob_day != "" && $user_dob_year != "" )
            {
                //setDateFormateDashed($data['user_dob'], "DD-MM-YYYY")
                $user_dob = $user_dob_year."-".$user_dob_month."-".$user_dob_day;
            } else {
                $user_dob = "";
            }
            $userDetails = User::create([
                'user_full_name' => $user_full_name,
                'user_email' => $user_email,
                'user_profile_screen_status' => $user_profile_screen_status,
                'user_password' => bcrypt($user_password),
                'user_dob' => $user_dob,
                'user_city' => $user_city,
                'user_city_other' => $user_city_other,
                'user_country' => $user_country,
                'user_about' => $user_about,
                'security_ques_id' => $security_question,
                'security_ans' => $security_answer,
                'user_image' => $userPicUrl,
                'user_ip_address' => $useripaddress,
                'user_lat' => $user_lat,
                'user_long' => $user_long,
                'user_gender' => $user_gender,
                'user_relationship_status' => $user_relationship_status,
                'user_interest' => $user_interest
            ]);
            
            $user_access_token = md5(time().$userDetails->user_id);
            
            $postdataarr = array(
                            'user_id' => $userDetails->user_id,
                            'user_access_token' => $user_access_token,
                            'user_device_type' => $user_device_type,
                            'user_device_token' => $user_device_token
                        );
                    
            $userObj->updateUserAccessToken($userDetails->user_id, $postdataarr);
            
            $userDetails['user_access_token'] = $user_access_token;
                    
            $responseStr = "You are registered successfully";

            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
    }
    
    /**
     * User registration
     *
     * @param  Request  $request
     * @return Response
     */
    public function userUpdateProfile(Request $request)
    {
        //
        $userPicUrl = "";
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $user_city  = isset($postdata['user_city']) ? $postdata['user_city'] : '';
        $hduserlat  = isset($postdata['hduserlat']) ? $postdata['hduserlat'] : '';
        $hduserlon  = isset($postdata['hduserlon']) ? $postdata['hduserlon'] : '';
        $user_dob_month  = isset($postdata['user_dob_month']) ? $postdata['user_dob_month'] : '';
        $user_dob_day  = isset($postdata['user_dob_day']) ? $postdata['user_dob_day'] : '';
        $user_dob_year  = isset($postdata['user_dob_year']) ? $postdata['user_dob_year'] : '';
        $user_full_name  = isset($postdata['user_full_name']) ? $postdata['user_full_name'] : '';
        $user_profile_screen_status  = isset($postdata['user_profile_screen_status']) ? $postdata['user_profile_screen_status'] : '';
        $user_country  = isset($postdata['user_country']) ? $postdata['user_country'] : '';
        $user_about  = isset($postdata['user_about']) ? $postdata['user_about'] : '';
        $user_relationship_status  = isset($postdata['user_relationship_status']) ? $postdata['user_relationship_status'] : '';
        $user_gender  = isset($postdata['user_gender']) ? $postdata['user_gender'] : '';
        $user_interest  = isset($postdata['user_interest']) ? $postdata['user_interest'] : '';
        
        
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {
            
            $userObj = new User;
            $user = User::findOrFail($user_id);
            
            $userProfileDet = $userObj->getUserProfileDetails($user_id);
            
            if( $userProfileDet ){
                $userProfileDetNew = $userProfileDet[0];
                $userPicUrl = $userProfileDetNew->user_image;
            }
            /*
            echo "<pre>";
            print_r($postdata);
            die(0); */
            $user_city_other = "";
            // getting all of the post data
            $user_image = array('image' => Input::file('user_image'));
            // setting up rules
            $rules = array('image' => 'required'); //mimes:jpeg,bmp,png and for max size max:10000
            // doing the validation, passing post data, rules and the messages
            $validator = Validator::make($user_image, $rules);

            if (Input::hasFile('user_image')) {
                if( $validator->fails() )
                {
                    $responseStr = "Please upload image";

                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                }            
            }

            if ( Input::hasFile('user_image') )
            {
                if (Input::file('user_image')->isValid()) {
                    $file_tmp = $_FILES['user_image']['tmp_name'];
                    $file_name = $_FILES['user_image']['name'];
                    
                    $userImageObj = new Userimage;
                    
                    if( $userPicUrl != "" )
                    {
                        $userImageDetails = array(
                                        'user_id' => $user_id,
                                        'user_image' => $userPicUrl,
                                     );
                        
                        $userImageObj->insertData($userImageDetails);
                    }
                    
                    $userPicUrl = uploadfile($file_tmp, $file_name, 'profile', 'images');
                    // sending back with message
                }
            }

            $useripaddress = getRealIP();

            if( $user_city == "" || $user_city == 0 )
            {
                $user_city_other = "Other";
            }

            $getuserlocation = getUserLocationFromIp($useripaddress);

            if( $hduserlat != "" && $hduserlon != "" )
            {
                $user_lat = $hduserlat;
                $user_long = $hduserlon;
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

            if( $user_dob_month != "" && $user_dob_day != "" && $user_dob_year != "" )
            {
                //setDateFormateDashed($data['user_dob'], "DD-MM-YYYY")
                $user_dob = $user_dob_year."-".$user_dob_month."-".$user_dob_day;
            } else {
                $user_dob = "";
            }
            $userDetails = array(
                    'user_full_name' => $user_full_name,
                    'user_profile_screen_status' => $user_profile_screen_status,
                    'user_dob' => $user_dob,
                    'user_city' => $user_city,
                    'user_city_other' => $user_city_other,
                    'user_country' => $user_country,
                    'user_about' => $user_about,
                    'user_image' => $userPicUrl,
                    'user_gender' => $user_gender,
                    'user_interest' => $user_interest
                );
            
            //print_r($userDetails);
            //die();
            $user->update($userDetails);
            
            /*
            $user_access_token = md5(time().$user_id);
            
            $postdataarr = array(
                            'user_id' => $user_id,
                            'user_access_token' => $user_access_token
                        );
                    
            $userObj->updateUserAccessToken($user_id, $postdataarr);
            
            $userDetails['user_access_token'] = $user_access_token;            
            */
            
            $responseStr = "Profile updated successfully";

            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
    }
    
    
    /**
     * User registration
     *
     * @param  Request  $request
     * @return Response
     */
    public function friendRequestSend(Request $request)
    {
        //
        $userPicUrl = "";
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $friend_request_status  = isset($postdata['friend_request_status']) ? $postdata['friend_request_status'] : '';
        $friend_block  = isset($postdata['friend_block']) ? $postdata['friend_block'] : '';
        $connect_message  = isset($postdata['connect_message']) ? $postdata['connect_message'] : '';
        $guest_id  = isset($postdata['friend_id']) ? $postdata['friend_id'] : '';
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        
        if( $user_id == "" || $guest_id == "" || $friend_request_status == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
        else
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
                        $responseStr = "Your friend request in pending";
            
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    } else if( $friend_status == 1 ) {
                        $responseStr = "You are already friend";
            
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    } else if( $friend_status == 2 && $friend_block_status == 0 ) {
                        $userFriendObj = new Userfriend;

                        $postdataarr = array(
                                        'user_id' => $user_id,
                                        'friend_id' => $guest_id,
                                        'friend_request_status' => 0
                                    );
                        $updtRes = $userFriendObj->updateFriendStatusDataAgain($postdataarr);
                        if( $updtRes ){
                            $responseStr = "Connect request sent sccessfully";
                            
                            $userDetails['user_id'] = $user_id;
                            $userDetails['friend_id'] = $guest_id;
                            $userDetails['friend_request_status'] = 0;
                        
                            $userSenderDetails = User::find($user_id);
                            $senderUsername = $userSenderDetails->user_full_name;

                            $userFriendDetails = User::find($guest_id);
                            
                            if( $userSenderDetails->user_image != "" )
                            {
                                $user_image_val = url().$userSenderDetails->user_image;
                            } else {
                                $user_image_val = "";
                            }
                            //PUSH NOTIFICATION
                            $notification_msg = ucwords($senderUsername)." sent you friend request";

                            if ($userFriendDetails->user_device_type == 1) {
                                //PUSH IPHONE
                                $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                                $pushiphoneres = pushIphone($userFriendDetails->user_device_token, $pushiphonepayload);
                            }

                            if ($userFriendDetails->user_device_type == 2) {
                                //PUSH ANDROID
                                $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                                $pushandroidres = andriodPush($userFriendDetails->user_device_token, $pushandroidpayload);
                            }
                            
                            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                            echo json_encode($data);
                            exit();
                        } else {
                            $responseStr = "Data not saved";
            
                            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                            echo json_encode($data);
                            exit();
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
                            $responseStr = "Connect request sent sccessfully";
                            
                            $userDetails['user_id'] = $user_id;
                            $userDetails['friend_id'] = $guest_id;
                            $userDetails['friend_request_status'] = 0;
                            
                            $userSenderDetails = User::find($user_id);
                            $senderUsername = $userSenderDetails->user_full_name;

                            $userFriendDetails = User::find($guest_id);
                            
                            if( $userSenderDetails->user_image != "" )
                            {
                                $user_image_val = url().$userSenderDetails->user_image;
                            } else {
                                $user_image_val = "";
                            }
                            
                            //PUSH NOTIFICATION
                            $notification_msg = ucwords($senderUsername)." sent you friend request";

                            if ($userFriendDetails->user_device_type == 1) {
                                //PUSH IPHONE
                                $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                                $pushiphoneres = pushIphone($userFriendDetails->user_device_token, $pushiphonepayload);
                            }

                            if ($userFriendDetails->user_device_type == 2) {
                                //PUSH ANDROID
                                $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                                $pushandroidres = andriodPush($userFriendDetails->user_device_token, $pushandroidpayload);
                            }
                        
                            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                            echo json_encode($data);
                            exit();
                        } else {
                            $responseStr = "Data not saved";
            
                            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                            echo json_encode($data);
                            exit();
                        }
                    } else if( $friend_block_status == 0 ) {
                        $responseStr = "Friend blocked successfully";
            
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
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
                        $responseStr = "Connect request sent sccessfully";
                        //print_r($insrtRes);
                        $userDetails = $insrtRes;
                        $userDetails['user_friend_id'] = $insrtRes->user_friend_id;
                        
                        $userSenderDetails = User::find($user_id);
                        $senderUsername = $userSenderDetails->user_full_name;
                        
                        if( $userSenderDetails->user_image != "" )
                        {
                            $user_image_val = url().$userSenderDetails->user_image;
                        } else {
                            $user_image_val = "";
                        }
                            
                        $userFriendDetails = User::find($guest_id);

                        //PUSH NOTIFICATION
                        $notification_msg = ucwords($senderUsername)." sent you friend request";

                        if ($userFriendDetails->user_device_type == 1) {
                            //PUSH IPHONE
                            $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                            $pushiphoneres = pushIphone($userFriendDetails->user_device_token, $pushiphonepayload);
                        }

                        if ($userFriendDetails->user_device_type == 2) {
                            //PUSH ANDROID
                            $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"friend_request", "user_id"=>$user_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 1);
                            $pushandroidres = andriodPush($userFriendDetails->user_device_token, $pushandroidpayload);
                        }
                            
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    } else {
                        $responseStr = "Data not saved";
            
                        $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
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
                    $responseStr = "Pending request deleted successfully";
                       
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                } else {
                    $responseStr = "Request not find";
            
                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
                    $responseStr = "Request accepted successfully";
                    
                    $userDetails['user_id'] = $user_id;
                    $userDetails['friend_id'] = $guest_id;
                    $userDetails['friend_request_status'] = $friend_request_status;
                    $userDetails['notify_friend_req_status'] = 1;
                    
                    $userSenderDetails = User::find($user_id);
                    $senderUsername = $userSenderDetails->user_full_name;

                    $userFriendDetails = User::find($guest_id);
                    $friendUsername = $userFriendDetails->user_full_name;
                    
                    if( $userFriendDetails->user_image != "" )
                    {
                        $user_image_val = url().$userFriendDetails->user_image;
                    } else {
                        $user_image_val = "";
                    }
                        
                    //PUSH NOTIFICATION
                    $notification_msg = ucwords($friendUsername)." accepted your friend request";

                    if ($userSenderDetails->user_device_type == 1) {
                        //PUSH IPHONE
                        $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$friendUsername, "type"=>"friend_accept", "user_id"=>$guest_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 2);
                        $pushiphoneres = pushIphone($userSenderDetails->user_device_token, $pushiphonepayload);
                    }

                    if ($userSenderDetails->user_device_type == 2) {
                        //PUSH ANDROID
                        $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$friendUsername, "type"=>"friend_accept", "user_id"=>$guest_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 2);
                        $pushandroidres = andriodPush($userSenderDetails->user_device_token, $pushandroidpayload);
                    }
                        
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                } else {
                    $responseStr = "Data not saved";
            
                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
                    $responseStr = "Request rejected successfully";
                    
                    $userDetails['user_id'] = $user_id;
                    $userDetails['friend_id'] = $guest_id;
                    $userDetails['friend_request_status'] = 2;
                    $userDetails['notify_friend_req_status'] = 1;
                    
                    $userSenderDetails = User::find($user_id);
                    $senderUsername = $userSenderDetails->user_full_name;
                    
                    $userFriendDetails = User::find($guest_id);
                    $friendUsername = $userFriendDetails->user_full_name;
                    
                    if( $userFriendDetails->user_image != "" )
                    {
                        $user_image_val = url().$userFriendDetails->user_image;
                    } else {
                        $user_image_val = "";
                    }
                    
                    //PUSH NOTIFICATION
                    $notification_msg = ucwords($friendUsername)." rejected your friend request";

                    if ($userSenderDetails->user_device_type == 1) {
                        //PUSH IPHONE
                        $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$friendUsername, "type"=>"friend_reject", "user_id"=>$guest_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 3);
                        $pushiphoneres = pushIphone($userSenderDetails->user_device_token, $pushiphonepayload);
                    }

                    if ($userSenderDetails->user_device_type == 2) {
                        //PUSH ANDROID
                        $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$friendUsername, "type"=>"friend_reject", "user_id"=>$guest_id, "msg"=>$connect_message, "user_img"=>$user_image_val, "message_id"=>"", "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 3);
                        $pushandroidres = andriodPush($userSenderDetails->user_device_token, $pushandroidpayload);
                    }
                    
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                } else {
                    $responseStr = "Data not saved";
            
                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
                    $responseStr = "Friend delete successfully from friend list";
                    
                    $userDetails['user_id'] = $user_id;
                    $userDetails['friend_id'] = $guest_id;
                    $userDetails['friend_request_status'] = 3;
                    $userDetails['notify_friend_req_status'] = 1;
                            
                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                } else {
                    $responseStr = "Data not saved";
            
                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
                        $responseStr = "Friend blocked";
                        
                        
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    }
                    else if( $friend_block == 0 )
                    {
                        $responseStr = "Friend un-blocked successfully";
                        
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    }
                } else {
                    $responseStr = "Data not saved";
            
                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                }
            }
             
        }
    }
    
    /*
     * Author:Jinandra
     * Date: 02-09-015
     * Accept and decline friend request
     */
    
    public function updateAcceptdeclinerequest() {
        
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $friend_request_status  = isset($postdata['friend_request_status']) ? $postdata['friend_request_status'] : '';
        $friend_id  = isset($postdata['friend_id']) ? $postdata['friend_id'] : '';
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        if( $user_id == "" || $friend_id == "" || $friend_request_status == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
        else
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
                                    'friend_request_status' => $friend_request_status,
                                    'notify_friend_req_status' => 1
                                );

                    $updtRes = $userFriendObj->updateFriendStatusData($postdataarr);
                    if( $updtRes ){
                        if( $friend_request_status == 1 )
                        {
                            $responseStr = "Friend request accepted successfully!";
                        } else if( $friend_request_status == 2 ) {
                            $responseStr = "Friend request declined successfully!";
                        }
                    
                        $userDetails['user_id'] = $user_id;
                        $userDetails['friend_id'] = $friend_id;
                        $userDetails['friend_request_status'] = 1;
                        $userDetails['notify_friend_req_status'] = 1;
                    
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                        
                    } else {
                        $responseStr = "Data not saved";
            
                        $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    }
                } 
                else if( $friend_status == 1 )
                {
                    $responseStr = "You are already friend";

                    $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                }
            }
            else
            {
                $responseStr = "No friend request available";

                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        }
        //die(0);        
    }
    
    
    /**
     * Get user change password
     *
     * @param  array  $data
     * @return User
     */
    public function changeUserPassword(Request $request) {
        
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $old_user_password  = isset($postdata['old_user_password']) ? $postdata['old_user_password'] : '';
        $user_password  = isset($postdata['user_password']) ? $postdata['user_password'] : '';
        $user_password_again  = isset($postdata['user_password_again']) ? $postdata['user_password_again'] : '';
       
        if( $user_id == "" || $user_password == "" || $old_user_password == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {

            if( $user_password == "" || $old_user_password == "" )
            {
                $responseStr = "Please enter your password";

                $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
            else
            {
                $validator = Validator::make(Input::all(),
                                array(
                                    'user_password' 	=> 'required',
                                    'old_user_password'	=> 'required|min:6',
                                    'user_password_again'=> 'required|same:user_password'
                                )
                            );
                
                if($validator->fails()) {
                    $responseStr = "Password and again password doesn't match";

                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();                
                } else {
                    
                    // Grab the current user
                    $user = User::find($user_id);
                    
                    // Get passwords from the user's input
                    $old_user_password 	= Input::get('old_user_password');
                    $user_password 	= Input::get('user_password');
                    
                    //print_r($user->getAuthPassword());
                    //print_r(Hash::check($old_user_password, $user->getAuthPassword()));
                    //die();
                    // test input password against the existing one
                    if(Hash::check($old_user_password, $user->getAuthPassword())){
                        $user->user_password = Hash::make($user_password);

                        // save the new password
                        if($user->save()) {
                            $responseStr = "Your password has been changed";

                            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                            echo json_encode($data);
                            exit();
                        }
                    } else {
                        $responseStr = "Your old password is incorrect";

                        $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    }
                
                }                
            }
        }
        //die(0);        
    }
    
    /**
     * Get blocked user list
     *
     * @param  array  $request
     * @return User
     */
    public function getBlockedUserList() {
        
        $resposeData = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {   
            
            $responseStr = "Success";
            
            $user_blocked_list = Userblockedfriend::getBlockedUser($user_id);
            $resposeData = $user_blocked_list;
                    
            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();            
        }       
    }
    
    
    /**
     * Get blocked user list
     *
     * @param  array  $request
     * @return User
     */
    public function userForgotPassword() {
        
        $resposeData = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_email  = isset($postdata['user_email']) ? $postdata['user_email'] : '';
        
        if( $user_email == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {   
            
            $randompass = randumpass(8);
            
            $userObj = new User;
            
            $userEmailData = User::getUserEmailCheck($user_email);
            
            if( $userEmailData ) {
                $postdataarr = array(
                                'user_password' => bcrypt($randompass)
                            );
                // print_r($postdataarr);
                // die();
                $userObj->update($postdataarr);

                $responseStr = "Password sent on your registered email address";
                $resposeData = array("new_password"=>$randompass);
                
                /*
                Mail::send('emails.userforgotpassword', ['new_password' => $randompass], function ($m) use ($user_email) {
                    $m->from('admin@flashfind.com', 'Flashfind');
                    $m->to($user_email)->subject('Forgot Password!');
                });
                */
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();   
            } else {
                
                $responseStr = "Sorry, email address not found";
  
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        }       
    }
    
    /**
     * Get user setting
     *
     * @param  array  $data
     * @return User
     */
    public function getFriendlist() {
        
        $resposeData = array();
        $extraParams = array();
        $all_friend_list = array();
        
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {  
            
            $all_friend = Userfriend::getFriendList($user_id);
        
            if($all_friend){             
                
                $responseStr = "Success";
                
                $j = 0;
                
                foreach( $all_friend as $user_image_listval)
                {
                    $all_interest_sender = array();
                    $all_interest_recv = array();
                    
                    $all_friend_list[$j] = (array) $user_image_listval;
                    //$all_friend_list[$j]['user_friend_id'] = $user_image_listval->user_friend_id;
                    //recv_user_interest
                    if( $user_image_listval->sender_user_interest != "" )
                    {
                        $user_interest_arr = explode(",", $user_image_listval->sender_user_interest);
                        //print_r($user_interest_arr);
                        //die();
                        $all_interest_sender = Interest::getUserInterest($user_interest_arr);
                    }
                    
                    $all_friend_list[$j]['sender_user_all_interest'] = $all_interest_sender;
                    
                    if( $user_image_listval->recv_user_interest != "" )
                    {
                        $user_interest_arr = explode(",", $user_image_listval->recv_user_interest);
                        //print_r($user_interest_arr);
                        //die();
                        $all_interest_recv = Interest::getUserInterest($user_interest_arr);
                    }
                    
                    $all_friend_list[$j]['recv_user_all_interest'] = $all_interest_recv;
                    //print_r($all_friend_list);
                    //die();
                    $j++;
                }
                
                //$all_friend_list = $all_friend;                
                $resposeData = $all_friend_list;
                    
                $resposeDataCount = count($resposeData);
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "totalrecord"=>$resposeDataCount, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();  
            
            } else {
                $responseStr = "No friends available";

                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        
        }       
    }
    
    
    /**
     * Get user setting
     *
     * @param  array  $data
     * @return User
     */
    public function getFriendNotificationlist() {
        
        $resposeData = array();
        $extraParams = array();
        $all_friend_list = array();
        $pending_friend_list = array();
        
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {  
            
            $pending_friend = Userfriend::getPendingFriendRequest($user_id);
        
            if($pending_friend){             
                
                $responseStr = "Success";
                $pending_friend_list = $pending_friend;
                
                $resposeData = $pending_friend_list;
                    
                $resposeDataCount = count($resposeData);
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "totalrecord"=>$resposeDataCount, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();  
            
            } else {
                $responseStr = "No friends notification available";

                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        
        }       
    }
    
    
    /**
     * User registration
     *
     * @param  Request  $request
     * @return Response
     */
    public function userUpdateProfileSecond(Request $request)
    {
        //
        $userPicUrl = "";
        $userDetails = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $user_full_name  = isset($postdata['user_full_name']) ? $postdata['user_full_name'] : '';
        $user_about  = isset($postdata['user_about']) ? $postdata['user_about'] : '';
               
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {
            
            $userObj = new User;
            $user = User::findOrFail($user_id);
            
            $userProfileDet = $userObj->getUserProfileDetails($user_id);
            
            if( $userProfileDet ){
                $userProfileDetNew = $userProfileDet[0];
                $userPicUrl = $userProfileDetNew->user_image;
            }
            /*
            echo "<pre>";
            print_r($postdata);
            die(0); */
            $user_city_other = "";
            // getting all of the post data
            $user_image = array('image' => Input::file('user_image'));
            // setting up rules
            $rules = array('image' => 'required'); //mimes:jpeg,bmp,png and for max size max:10000
            // doing the validation, passing post data, rules and the messages
            $validator = Validator::make($user_image, $rules);

            if (Input::hasFile('user_image')) {
                if( $validator->fails() )
                {
                    $responseStr = "Please upload image";

                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
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
            }

            $useripaddress = getRealIP();

            if( $user_city == "" || $user_city == 0 )
            {
                $user_city_other = "Other";
            }

            $getuserlocation = getUserLocationFromIp($useripaddress);

            if( $hduserlat != "" && $hduserlon != "" )
            {
                $user_lat = $hduserlat;
                $user_long = $hduserlon;
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

            if( $user_dob_month != "" && $user_dob_day != "" && $user_dob_year != "" )
            {
                //setDateFormateDashed($data['user_dob'], "DD-MM-YYYY")
                $user_dob = $user_dob_year."-".$user_dob_month."-".$user_dob_day;
            } else {
                $user_dob = "";
            }
            $userDetails = array(
                    'user_full_name' => $user_full_name,
                    'user_profile_screen_status' => $user_profile_screen_status,
                    'user_dob' => $user_dob,
                    'user_city' => $user_city,
                    'user_city_other' => $user_city_other,
                    'user_country' => $user_country,
                    'user_about' => $user_about,
                    'user_image' => $userPicUrl,
                    'user_gender' => $user_gender,
                );
            
            // print_r($postdataarr);
            // die();
            $user->update($userDetails);
            
            $user_access_token = md5(time().$user_id);
            
            $postdataarr = array(
                            'user_id' => $user_id,
                            'user_access_token' => $user_access_token
                        );
                    
            $userObj->updateUserAccessToken($user_id, $postdataarr);
            
            $userDetails['user_access_token'] = $user_access_token;            
            
            
            $responseStr = "Profile updated successfully";

            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$userDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
    }
    
    
    /**
     * Get user setting
     *
     * @param  array  $data
     * @return User
     */
    public function getUserImagelist() {
        
        $resposeData = array();
        $extraParams = array();
        $all_user_image_list = array();
        
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        
        if( $user_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {  
            
            $user_image_list = Userimage::getAllUserImage($user_id);
        
            if($user_image_list){             
                
                $responseStr = "Success";
                $all_user_image_list = $user_image_list;
                
                $resposeData = $all_user_image_list;
                    
                $resposeDataCount = count($resposeData);
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "totalrecord"=>$resposeDataCount, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();  
            
            } else {
                $responseStr = "No image available";

                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        
        }       
    }
    
}
