<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
 */
    protected $table = 'ff_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['admin_id', 'user_name', 'user_password', 'user_email'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_password', 'remember_token'];
    
    /**
     * Get searched user
     *
     * @param  array  $columns
     * @return \App\Models\User
     */
    public static function authAdmindetails(Array $data)
    {        
        $admindetail = DB::table((new Admin)->getTable().' as A')
                     ->select('A.*')
                     ->where('A.user_name', '=', $data['user_name'])
                     ->where('A.user_password', '=', $data['user_password'])
                     ->get();
        if( $admindetail )
        {
            return $admindetail;
        }
        else{
            return false;
        }
    }
    
    /**
     * check old password
     *
     * @param  array  $columns
     * @return \App\Models\Admin
     */
    public static function checkOldPassword(Array $data)
    {        
        $admindetail = DB::table((new Admin)->getTable().' as A')
                     ->select('A.*')
                     ->where('A.user_password', '=', $data['user_password'])
                     ->get();
        if( $admindetail )
        {
            return $admindetail;
        }
        else{
            return false;
        }
    }
    
    
    /**
     * Update admin password
     *
     * @param  array  $columns
     * @return User
     */
    public static function updateAdminPassword(Array $data)
    {   
        $updateRes = DB::table((new Admin)->getTable())
                     ->where('admin_id', '=', $data['admin_id'])
                     ->update(['user_password' => $data['user_password']]);
        if( $updateRes )
        {
            return true;
        }
        else{
            return false;
        }
    }

}
