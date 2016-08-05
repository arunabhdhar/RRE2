<?php
namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Userfriend;
use App\Models\Userchat;
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

class InterestController extends Controller
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
    public function interestList()
    {
        $responseDetails = array();
        $extraParams = array();
        
        $all_interest = Interest::getInterestList();
        if($all_interest){
            $responseDetails = $all_interest;
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
