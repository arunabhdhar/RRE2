<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Input;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

class IndexController extends Controller
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
    public function __construct()
    {       
        
    }
    
    public function getIndex() {
        $all_user = User::getRecentUser();
        
        return view('welcome', ['recent_user' => $all_user]); 
    }
    
    /**
     * Get user profile details
     *
     * @param  array  $data
     * @return User
     */
    public function getSearcheduserauto() {
        
        $search_key = Input::get("searchkey");
        $all_searched_user = array();
        $all_friend_list = array();
        
        if( $search_key != "" ){
            if(Auth::check())
            {
                $user_id = Auth::user()->user_id;
            }
            else {
                $user_id ="";
            }
            
            $all_searched_user = User::getSearchedUserAuto($search_key, $user_id);
            
            
            if(!empty($all_searched_user)) {
           
                $searchstr = "<ul id=\"country-list\">";
            
                foreach($all_searched_user as $search_val) {
                    $searchstr.="<li onClick=\"selectCountry('".$search_val->user_full_name."');\">".$search_val->user_full_name."</li>";
                }
                $searchstr.="</ul>";
            } else {
                $searchstr = "<ul id=\"country-list\">";
                $searchstr.="<li>Having trouble finding someone? Type in their email address.</li>";
                $searchstr.="</ul>";
            }
        }
        else{
            $searchstr = "";
        }
        
        echo $searchstr;
    }
    
}
