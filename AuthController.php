<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_full_name' => 'required|max:100',
            'user_email' => 'required|email|max:150|unique:rr_users',
            'user_password' => 'required|confirmed|min:6',
            'user_gender' => 'required',   
         ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        
        
        $userPicUrl = "";
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
        }
        
        $useripaddress = getRealIP();
        
        $getuserlocation = getUserLocationFromIp($useripaddress);
        
        if( $data['hduserlat'] != "" && $data['hduserlon'] != "" )
        {
            $user_lat = $data['hduserlat'];
            $user_long = $data['hduserlon'];
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
        
        if( $data['user_dob'] )
        {
            $user_dob = setDateFormateDashed($data['user_dob'], "DD-MM-YYYY");
            //$user_dob = $data['user_dob_year']."-".$data['user_dob_month']."-".$data['user_dob_day'];
        } else {
            $user_dob = "";
        }
        return User::create([
            'user_full_name' => $data['user_full_name'],
            'user_email' => $data['user_email'],
            'user_password' => bcrypt($data['user_password']),
            'user_dob' => $user_dob,
            'user_image' => $userPicUrl,
            'user_ip_address' => $useripaddress,
            'user_lat' => $user_lat,
            'user_long' => $user_long,
            'user_gender' => $data['user_gender'],
        ]);
        
    }
}
