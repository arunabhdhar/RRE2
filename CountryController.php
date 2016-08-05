<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

class CountryController extends Controller
{
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {        
 
    }
    
    public function getIndex() {

    }
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getCountryval() {
        
        $country_id = Input::get("countryid");
        $citylistoncountry = "";
        
        if( $country_id != "" ){
            $all_city_on_country = City::getCityListOnCuntry($country_id);
            if($all_city_on_country){
                
                $citylistoncountry = "<select name=\"user_city\" id=\"user_city\" class=\"validate[required] field-2\">
                                        <option value=\"\">---Select State*---</option>";
                foreach( $all_city_on_country as $cityval )   
                {
                    $citylistoncountry.="<option value=\"".$cityval->city_id."\">".$cityval->city_name."</option>";
                }
                $citylistoncountry.="</select>";
            }
            else{
                 $citylistoncountry = "<select name=\"user_city\" id=\"user_city\" class=\"validate[required] field-2\">
                                        <option value=\"\">---Select State*---</option>";
                $citylistoncountry.="<option value=\"0\">Other</option>";
                $citylistoncountry.="</select>";
            }
        }
        
        if( $citylistoncountry != "" )
        {
            echo $citylistoncountry;
        }
        else{
            echo "Not-Done";
        }
        //die(0);        
    }
}
