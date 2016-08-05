<?php
namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Country;
use App\Models\City;
use Validator;
use Session;
use Input;
use Auth;
use Redirect;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Transformers\SchoolTransformer;
use App\Http\Controllers\Controller;

class CountryController extends Controller
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
    public function countryList()
    {
        $responseDetails = array();
        $extraParams = array();
        
        $all_country = Country::getCountryList();
        if($all_country){
            $responseDetails = $all_country;
            $responseStr = "Success";

            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        }
        else{
            $responseStr = "Data not found";

            $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
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
    public function countryCityList(Request $request)
    {
        $country_id = Input::get("countryid");
        $responseDetails = array();
        $extraParams = array();
        $citylistoncountry = "";
        
        if( $country_id == "" )
        {	
            $responseStr = "All fields are required!";
            
            $data = array("status" => 0, "error_code" => 1, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
            echo json_encode($data);
            exit();
        } else {
            $all_city_on_country = City::getCityListOnCuntry($country_id);
            if($all_city_on_country){
                $responseDetails = $all_city_on_country;
                $responseStr = "Success";
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
            else{
                $responseStr = "Data not found";
                
                $data = array("status" => 1, "error_code" => 0, "response_string" => $responseStr, "data" =>$responseDetails, "additionalData" => $extraParams);
                echo json_encode($data);
                exit();
            }
        }        
    }   
}
