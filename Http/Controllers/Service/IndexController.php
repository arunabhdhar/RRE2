<?php

namespace App\Http\Controllers\Service;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Input;
use Auth;
use App\Http\Controllers\Controller;

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
    
    public function getIndex() 
    {
        echo "I'm in service";
    }   
    
}
