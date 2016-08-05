<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
    */
    protected $table = 'rr_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_full_name', 'user_email', 'user_image', 'user_contact_number', 'user_password', 'user_dob', 'user_lat', 'user_long', 'user_ip_address', 'user_gender', 'user_access_token', 'user_device_token', 'user_device_type', 'user_company_name', 'user_website'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_password', 'remember_token'];
    
    
    /**
     * Get all user on admin panel
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getAllUser()
    {        
        $users = DB::table((new User)->getTable())
                         ->select(DB::raw('*'))
                         ->orderBy('user_id', 'desc')
                         ->paginate(10);

        return $users;
    }
    
    /**
     * Get recent user on home page
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getRecentUser()
    {        
        if(Auth::check())
        {
            $user_id = Auth::user()->user_id;
        
            $users = DB::table((new User)->getTable())
                         ->select(DB::raw('*'))
                         ->where('status', '=', 1)
                         ->where('user_id', '!=', $user_id)
                         ->orderBy('user_id', 'desc')
                         ->get(5);
        }
        else {
            $users = DB::table((new User)->getTable())
                         ->select(DB::raw('*'))
                         ->where('status', '=', 1)
                         ->orderBy('user_id', 'desc')
                         ->get(5);
        }

        return $users;
    }
    
    /**
     * Get searched user
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getSearchedUserAuto($search_key, $user_id)
    {        
        
        $users = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'CT.city_name', 'C.nicename')
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where(function($query) use ($search_key){
                            $query->where('U.user_full_name', 'like', '%'.$search_key.'%')
                                  ->orWhere('U.user_email', 'like', '%'.$search_key.'%');
                        })
                     ->where('U.status', '=', 1)
                     ->where('U.user_id', '!=', $user_id)
                     //->orderBy('U.user_id', 'desc')
                     ->get(6);
                    //->toSql();
        //echo "<pre>";
        //print_r($users);
          //dd(DB::getQueryLog());

        return $users;
    }
    
    /**
     * Get searched user
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getSearchedUser($search_key, $user_id)
    {      
        $searchTerms = array();
       //echo $search_key;
        $searchTerms = explode(' ', $search_key);
        //print_r($searchTerms);
        $users = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'CT.city_name', 'C.nicename')
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where(function($query) use ($search_key, $searchTerms){
                            foreach($searchTerms as $term)
                            {
                               $query->orWhere('U.user_full_name', 'like', '%'.$term.'%');
                            }
                            $query->orWhere('U.user_email', 'like', '%'.$search_key.'%');
                        })
                     ->where('U.status', '=', 1)
                     ->where('U.user_id', '!=', $user_id)
                     ->orderBy('U.user_id', 'desc')
                     ->paginate(10);
                     //->get();
                     //->toSql();
        //echo "<pre>";
       // print_r($users);
        //dd(DB::getQueryLog());

        return $users;
    }
    
    /**
     * Get user profile
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getUserProfileDetails($user_id)
    {        
        $users = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'CT.city_name', 'C.nicename')
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where('U.user_id', '=', $user_id)
                     ->where('U.status', '=', 1)
                     ->get();
                     //->toSql();
        //echo "<pre>";
        //print_r($users);
       //   dd(DB::getQueryLog());
        return $users;
    }
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $user_lat latitude of the point of interest
     *  @param float $user_long longitude of the point of interest
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getSearchedUserNearBy($user_lat, $user_long, $user_id, $max_distance = 600, $units = 'miles', $page=1, $user_gender="", $user_interest="")
    {        
        $numPerPage = 10;
        $page = (Input::get('page')) ? 1 : 1;
        
        if(empty($user_lat)){
     		$user_lat = 0;
     	}
     	if(empty($user_long)){
     		$user_long = 0;
     	}
        
        if( $user_interest != "" ){
            $user_interest = explode(",", $user_interest);
        } 
        
        //print_r($user_interest);
        /*
         *  Allow for changing of units of measurement
         */
        switch ( $units ) {
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 5;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }
        
        /*
         *  Generate the select field for disctance
         */
        $distance_select = sprintf(
                                    "           
                                    ROUND(( %d * acos( cos( radians(%s) ) " .
                                            " * cos( radians( U.user_lat ) ) " .
                                            " * cos( radians( U.user_long ) - radians(%s) ) " .
                                            " + sin( radians(%s) ) * sin( radians( U.user_lat ) ) " .
                                        " ) " . 
                                    ")
                                            , 2 ) " . 
                                    "AS distance
                                    ",
                                    $gr_circle_radius,               
                                    $user_lat,
                                    $user_long,
                                    $user_lat
                                       );
        //, ( 3959 * acos( cos( radians("' . $user_lat . '") ) * cos( radians( U.user_lat ) ) * cos( radians( U.user_long ) - radians("' . $user_long . '") ) + sin( radians("' . $user_lat .'") ) * sin( radians(U.user_lat) ) ) ) AS distance
        
        $total = DB::table((new User)->getTable().' as U')
                     ->select(DB::raw('U.*, CT.city_name, C.nicename,' .  $distance_select ))
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where('U.status', '=', 1)
                     ->where(function ($query) use($user_gender){
                         if($user_gender != "")
                            $query->where('U.user_gender', '=', $user_gender);
                      }) 
                     ->where(function ($query) use($user_interest){
                            if($user_interest != ""){
                             
                                foreach( $user_interest as $user_interest_val){
                                    $user_interest_val = array($user_interest_val);
                                    //$query->whereRaw("U.user_interest in ('".str_replace(",","','",$user_interest)."')");
                                    $query->orWhereRaw('find_in_set(?, U.user_interest)',$user_interest_val);
                                    //$query->whereIn('U.user_interest', $user_interest);
                                }
                            }
                      }) 
                     ->where('U.user_id', '!=', $user_id)
                     ->having('distance', '<=', $max_distance )
                     ->get();
        $totalusers = count($total);
        
        $users = DB::table((new User)->getTable().' as U')
                     ->select(DB::raw('U.*, CT.city_name, C.nicename,' .  $distance_select ))
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where('U.status', '=', 1)
                     ->where('U.user_id', '!=', $user_id)
                     ->where(function ($query) use($user_gender){
                         if($user_gender != "")
                            $query->where('U.user_gender', '=', $user_gender);
                      }) 
                     ->where(function ($query) use($user_interest){
                            if($user_interest != ""){
                             
                                foreach( $user_interest as $user_interest_val){
                                    $user_interest_val = array($user_interest_val);
                                    //$query->whereRaw("U.user_interest in ('".str_replace(",","','",$user_interest)."')");
                                    $query->orWhereRaw('find_in_set(?, U.user_interest)',$user_interest_val);
                                    //$query->whereIn('U.user_interest', $user_interest);
                                }
                            }
                      })
                     ->having('distance', '<=', $max_distance )
                     ->orderBy('distance', 'ASC')
                     ->take($numPerPage)
                     ->offset(($page-1) * $numPerPage)
                     //->paginate(10);
                     ->get();
                     //->toSql();
        //echo "<pre>";
        //print_r($users);
        //die();
        $user_result = new Paginator($users, $totalusers, $numPerPage, array($page), array("path" => '/searchuser/searchednearbyuserlist'));
        //$pagination = Paginator::make($users, count($users), 10);
        //$pagination = new Paginator($users, $perPage);;
        //echo "<pre>";
        //print_r($users);
        //dd(DB::getQueryLog());
        return $user_result;
    }
    
    /**
     * Get pending friend requests
     *
     * @param  array  $columns
     * @return User
     */
    public static function updateProfileCover($user_id, Array $data)
    {   
        $updateRes = DB::table((new User)->getTable())
                     ->where('user_id', '=', $user_id)
                     ->update(['user_profile_cover' => $data['user_profile_cover']]);
        
        if( $updateRes )
        {
            return $updateRes;
        }
        else{
            return false;
        }
    }
    
    
    /**
     * Get pending friend requests
     *
     * @param  array  $columns
     * @return User
     */
    public static function updateUserInterest($user_id, Array $data)
    {   
        $updateRes = DB::table((new User)->getTable())
                     ->where('user_id', '=', $user_id)
                     ->update(['user_interest' => $data['user_interest']]);
        
        if( $updateRes )
        {
            return $updateRes;
        }
        else{
            return false;
        }
    }
    
    /**
     * Get user profile login
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getUserLogin( Array $data )
    {        
        $users = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'CT.city_name', 'C.nicename')
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where('U.user_email', '=', $data['user_email'])
                     ->where('U.user_password', '=', $data['user_password'])
                     ->get();
                     //->toSql();
        //echo "<pre>";
        //print_r($users);
       //   dd(DB::getQueryLog());
        return $users;
    }
    
    /**
     * Get pending friend requests
     *
     * @param  array  $columns
     * @return User
     */
    public static function updateUserAccessToken($user_id, Array $data)
    {   
        
        $updateRes = DB::table((new User)->getTable())
                     ->where('user_id', '=', $user_id)
                     ->update(['user_access_token' => $data['user_access_token'], 'user_device_token' => $data['user_device_token'], 'user_device_type' => $data['user_device_type']]);
        
        if( $updateRes )
        {
            return $updateRes;
        }
        else{
            return false;
        }
    }
    
    /**
     * Get user profile login
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function getUserEmailCheck( $user_email )
    {        
        $users = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'CT.city_name', 'C.nicename')
                     ->leftJoin((new Country)->getTable().' as C', 'C.country_id', '=', 'U.user_country')
                     ->leftJoin((new City)->getTable().' as CT', 'CT.city_id', '=', 'U.user_city')
                     ->where('U.user_email', '=', $user_email)
                     ->get();
                     //->toSql();
        //echo "<pre>";
        //print_r($users);
       //   dd(DB::getQueryLog());
        return $users;
    }

}
