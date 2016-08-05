<?php
namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Userfriend;
use App\Models\Userblockedfriend;
use App\Models\Country;
use App\Models\City;
use App\Models\Userchat;
use Validator;
use Session;
use Input;
use Auth;
use Mail;
use Redirect;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
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
     * Get user chat
     *
     * @param  array  $request
     * @return User
     */
    public function userChat() {
        
        $resposeData = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $friend_id  = isset($postdata['friend_id']) ? $postdata['friend_id'] : '';
        $send_message  = isset($postdata['send_message']) ? $postdata['send_message'] : '';
        
        if( $user_id == "" || $friend_id == "" || $send_message == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {   
            
            if(strlen($send_message) >= 140 ){
                $responseStr = "Message should not be greater than 140 character";
            
                $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            } else {
                
                $userChatObj = new Userchat;
                
                $send_user_id = $user_id;
                $recv_user_id = $friend_id;
                
                if( $recv_user_id )
                {
                    $postdataarr = array(
                                    'send_user_id' => $send_user_id,
                                    'recv_user_id' => $recv_user_id,
                                    'chat_message' => $send_message,
                                    'message_read_status' => 0                    
                                );             
                    
                    $insrtRes = $userChatObj->insertData($postdataarr);

                    if( $insrtRes ){
                        $friend_chat_id_val = $insrtRes->friend_chat_id;
                        
                        $resposeData = $insrtRes;
                        $responseStr = "Your Message sent successfully";
                        
                        $userSenderDetails = User::find($send_user_id);
                        $senderUsername = $userSenderDetails->user_full_name;

                        $userFriendDetails = User::find($recv_user_id);
                        $friendUsername = $userFriendDetails->user_full_name;
                        
                        if( $userSenderDetails->user_image != "" )
                        {
                            $user_image_val = url().$userSenderDetails->user_image;
                        } else {
                            $user_image_val = "";
                        }
                        
                        //PUSH NOTIFICATION
                        $notification_msg = ucwords($senderUsername)." sent you a message, please check";

                        if ($userFriendDetails->user_device_type == 1) {
                            //PUSH IPHONE
                            $pushiphonepayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"chat", "user_id"=>$send_user_id, "msg"=>$send_message, "user_img"=>$user_image_val, "message_id"=>$friend_chat_id_val, "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 4);
                            $pushiphoneres = pushIphone($userFriendDetails->user_device_token, $pushiphonepayload);
                        }

                        if ($userFriendDetails->user_device_type == 2) {
                            //PUSH ANDROID
                            $pushandroidpayload['aps'] = array("alert" => $notification_msg, "sound" => "Default", "user_name"=>$senderUsername, "type"=>"chat", "user_id"=>$send_user_id, "msg"=>$send_message, "user_img"=>$user_image_val, "message_id"=>$friend_chat_id_val, "data" => $notification_msg, "pushtype" => "voice", "badge" => 0, "push_id" => 4);
                            $pushandroidres = andriodPush($userFriendDetails->user_device_token, $pushandroidpayload);
                        }
                    
                        $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    } else {
                        $responseStr = "Sorry, your Message not sent";
                        
                        $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                        echo json_encode($data);
                        exit();
                    }
                }
                else 
                {
                    $responseStr = "Sorry, please send receiver id";

                    $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                    echo json_encode($data);
                    exit();
                }
            }
            
        }       
    }
    
    
    /**
     * Get user chat list
     *
     * @param  array  $request
     * @return User
     */
    public function getChat() {
        
        $resposeData = array();
        $extraParams = array();
        $postdata = Input::all();
        
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $friend_id  = isset($postdata['friend_id']) ? $postdata['friend_id'] : '';
        $last_message_id  = isset($postdata['last_message_id']) ? $postdata['last_message_id'] : '';
        
        if( $user_id == "" || $friend_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {   
            
            if( $last_message_id == 0 ) {
                $user_chat = Userchat::getChatMessageList($user_id, $friend_id);
            } else {
                $user_chat = Userchat::getLastChatMessage($user_id, $friend_id, $last_message_id);
            }
            //echo "<pre>";
            // echo get_last_query();
            // print_r($user_chat);
            // die(0);
            if( $user_chat ){
                $resposeData = $user_chat;  
                
                $responseStr = "Success";
                        
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            } else {
                $responseStr = "Sorry, chat list is blank";
                        
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$resposeData, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
            
        }       
    }
    
    
}
