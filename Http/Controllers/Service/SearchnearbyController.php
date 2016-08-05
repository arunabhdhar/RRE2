<?php
namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\Interest;
use Validator;
use Session;
use Input;
use Auth;
use Redirect;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Transformers\SchoolTransformer;
use App\Http\Controllers\Controller;

class SearchnearbyController extends Controller
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
    public function searchedNearByUserList()
    {
        
        $extraParams = array();
        $responseDetails = array();
        $searched_users = array();
        $postdata = Input::all();
        
        
        //echo "============post date===============";
        //echo "<pre>";
        //print_r($postdata);
        //die(0);
        $distance = 6000;
        $user_lat  = isset($postdata['user_lat']) ? $postdata['user_lat'] : '';
        $user_long  = isset($postdata['user_long']) ? $postdata['user_long'] : '';
        $user_gender  = isset($postdata['user_gender']) ? $postdata['user_gender'] : '';
        $user_interest  = isset($postdata['user_interest']) ? $postdata['user_interest'] : '';
        $user_id  = isset($postdata['user_id']) ? $postdata['user_id'] : '';
        $page_id  = isset($postdata['page_id']) ? $postdata['page_id'] : '';
        
        if( $user_lat != "" && $user_long != "" ){
            $i = 0;
            $all_searched_user = User::getSearchedUserNearBy($user_lat, $user_long, $user_id, $distance, 'miles', $page_id, $user_gender, $user_interest);
            foreach( $all_searched_user as $all_searched_user_val ){
                $searched_users[$i] = $all_searched_user_val;
                $i++;
            }
            
            if( !empty($searched_users) )
            {
                $k = 0;
                foreach( $searched_users as $keyname=>$searched_users_val){
                    $all_interest = array();
                    
                    $responseDetails[$k]['user_id'] = $searched_users_val->user_id;
                    $responseDetails[$k]['user_full_name'] = $searched_users_val->user_full_name;
                    $responseDetails[$k]['user_contact_number'] = $searched_users_val->user_contact_number;
                    $responseDetails[$k]['user_email'] = $searched_users_val->user_email;
                    $responseDetails[$k]['user_image'] = $searched_users_val->user_image;
                    $responseDetails[$k]['user_dob'] = $searched_users_val->user_dob;
                    $responseDetails[$k]['city_name'] = $searched_users_val->city_name;
                    $responseDetails[$k]['user_profile_screen_status'] = $searched_users_val->user_profile_screen_status;
                    $responseDetails[$k]['nicename'] = $searched_users_val->nicename;
                    
                    /*1->Single, 2->Married, 3->It's complicated, 4->In relationship, 
                        5-> Engaged, 6->In a open relationship, 7->Divorced */
                    if( $searched_users_val->user_relationship_status == 1 ){
                        $responseDetails[$k]['user_relationship_status'] = "Single";
                    }elseif( $searched_users_val->user_relationship_status == 2 ){
                        $responseDetails[$k]['user_relationship_status'] = "Married";
                    }elseif( $searched_users_val->user_relationship_status == 3 ){
                        $responseDetails[$k]['user_relationship_status'] = "It's complicated";
                    }elseif( $searched_users_val->user_relationship_status == 4 ){
                        $responseDetails[$k]['user_relationship_status'] = "In relationship";
                    }elseif( $searched_users_val->user_relationship_status == 5 ){
                        $responseDetails[$k]['user_relationship_status'] = "Engaged";
                    }elseif( $searched_users_val->user_relationship_status == 6 ){
                        $responseDetails[$k]['user_relationship_status'] = "In a open relationship";
                    }elseif( $searched_users_val->user_relationship_status == 7 ){
                        $responseDetails[$k]['user_relationship_status'] = "Divorced";
                    }
                    
                    
                    
                    if( $searched_users_val->user_interest != "" )
                    {
                        $user_interest_arr = explode(",", $searched_users_val->user_interest);
                        //print_r($user_interest_arr);
                        $all_interest = Interest::getUserInterest($user_interest_arr);
                    }
                    $responseDetails[$k]['user_all_interest'] = $all_interest;
                    
                    $k++;
                }
                //print_r($responseDetails);
                //die(0);
                $responseStr = "Success";
            }else {
                $responseStr = "Data not found";
            }           
            
            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
        else{
            
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
        
    }

    
}
