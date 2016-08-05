<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

/*
  Route::get('/home', function () {
  return view('dashboard');
  });
 */

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

#Route::post('auth/profileupdate', 'Auth\AuthController@postProfile');
// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


// User login routes...
Route::controller('user', 'UserController', [
    'middleware' => 'auth',
    'getIndex' => 'user.login',
]);
// User profile routes...
Route::controller('user', 'UserController', [
    'middleware' => 'auth',
    'getProfile' => 'user.profile',
]);
// User login routes...
Route::controller('user', 'UserController', [
    'middleware' => 'auth',
    'getSetting' => 'user.setting',
]);

// Authentication routes...
Route::controller('guestuser', 'GuestuserController', [
    'getProfile' => 'guestuser.profile',
]);

// Country and city get routes...
Route::controller("country", "CountryController");

// Country and city get routes...
Route::controller("searchuser", "SearchuserController");

// index get routes...
Route::controller("index", "IndexController");

// Admin home routes...
Route::get('admin', 'Admin\AdminController@getIndex');
Route::get('admin/login', 'Admin\AdminController@getLogin');
Route::get('admin/changepassword', 'Admin\AdminController@getChangepassword');
Route::get('admin/logout', 'Admin\AdminController@getLogout');

Route::post('admin/loginsubmit', 'Admin\AdminController@postLoginsubmit');
Route::post('admin/changepasswordsubmit', 'Admin\AdminController@postChangepasswordsubmit');


Route::get('admin/welcome', 'Admin\AdminController@getWelcome');

Route::get('admin/user', 'Admin\UserController@getIndex');

Route::get('admin/interest', 'Admin\InterestController@getIndex');
Route::get('admin/interest/add', 'Admin\InterestController@getAdd');
Route::get('admin/interest/edit', 'Admin\InterestController@getEdit');
Route::get('admin/interest/deleteinterest', 'Admin\InterestController@getDeleteinterest');

Route::post('admin/interest/addinterest', 'Admin\InterestController@postAddinterest');
Route::post('admin/interest/editinterest', 'Admin\InterestController@postEditinterest');


// Service routes...
Route::get('service', 'Service\IndexController@getIndex');

Route::filter('throttle', function() {
    $ip = $_SERVER["REMOTE_ADDR"];

    if (!Cache::get('throttle_count_' . $ip)) {
        Cache::put('throttle_count_' . $ip, 1, 5);
    } else {
        Cache::increment('throttle_count_' . $ip, 1);
    }

    if (Cache::get('throttle_count_' . $ip) > 100) {
        return Response::json(['error' => [
                        'message' => 'You reached the maximum number of requests. Please try again in 5 minutes'
                    ]
                        ], 403);
    }
});


Route::group(['prefix' => 'service/v1', 'before' => 'auth.basic|throttle'], function() {
    Route::group(['prefix' => 'user'], function() {
        Route::get('', ['uses' => 'Service\UserController@index']);
        Route::post('', ['uses' => 'Service\UserController@saveUser']);
        Route::post('userlogin', ['uses' => 'Service\UserController@userLogin']);
        Route::post('userimagelist', ['uses' => 'Service\UserController@getUserImagelist']);
        Route::post('userregistration', ['uses' => 'Service\UserController@userRegistration']);
        Route::post('userprofileupdate', ['uses' => 'Service\UserController@userUpdateProfile']);
        Route::post('sendfriendrequest', ['uses' => 'Service\UserController@friendRequestSend']);
        Route::post('acceptdeclinefriendrequest', ['uses' => 'Service\UserController@updateAcceptdeclinerequest']);
        Route::post('changepassword', ['uses' => 'Service\UserController@changeUserPassword']);
        Route::post('blockeduserlist', ['uses' => 'Service\UserController@getBlockedUserList']);
        Route::post('userforgotpassword', ['uses' => 'Service\UserController@userForgotPassword']);
        Route::post('userfriendlist', ['uses' => 'Service\UserController@getFriendlist']);
        Route::post('userfriendnotificationlist', ['uses' => 'Service\UserController@getFriendNotificationlist']);
    });
    //Route::resource('user', 'Service\UserController');
});


Route::group(['prefix' => 'service/v1', 'before' => 'auth.basic|throttle'], function() {
    Route::group(['prefix' => 'country'], function() {
        Route::get('', ['uses' => 'Service\CountryController@index']);
        Route::get('countrylist', ['uses' => 'Service\CountryController@countryList']);
        Route::post('citylist', ['uses' => 'Service\CountryController@countryCityList']);
   });
    //Route::resource('user', 'Service\UserController');
});


Route::group(['prefix' => 'service/v1', 'before' => 'auth.basic|throttle'], function() {
    Route::group(['prefix' => 'searchnearby'], function() {
        Route::get('', ['uses' => 'Service\SearchnearbyController@index']);
        Route::post('searchnearbyuserlist', ['uses' => 'Service\SearchnearbyController@searchedNearByUserList']);
   });
    //Route::resource('user', 'Service\UserController');
});

Route::group(['prefix' => 'service/v1', 'before' => 'auth.basic|throttle'], function() {
    Route::group(['prefix' => 'chat'], function() {
        Route::get('', ['uses' => 'Service\ChatController@index']);
        Route::post('userchat', ['uses' => 'Service\ChatController@userChat']);
        Route::post('userchatlist', ['uses' => 'Service\ChatController@getChat']);
   });
});

Route::group(['prefix' => 'service/v1', 'before' => 'auth.basic|throttle'], function() {
    Route::group(['prefix' => 'interest'], function() {
        Route::get('', ['uses' => 'Service\InterestController@index']);
        Route::post('interestlist', ['uses' => 'Service\InterestController@interestList']);
   });
    //Route::resource('user', 'Service\UserController');
});

// Authentication routes...
Route::controller('/', 'IndexController', [
    'getIndex' => 'welcome',
]);



