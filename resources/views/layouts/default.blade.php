<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body>
<div class="acontainer">

    <header class="arow">
        <?php $currentActionName = Route::getCurrentRoute()->getActionName(); // Array with full controller info ?>
		@if(Auth::check())
			@include('includes.headerlogin')
                @elseif($currentActionName == 'App\Http\Controllers\Auth\AuthController@getLogin' || $currentActionName == 'App\Http\Controllers\Auth\AuthController@getRegister' || $currentActionName == 'App\Http\Controllers\Auth\PasswordController@getEmail' )
			@include('includes.headerbeforelogin')
		@else
			@include('includes.header')
		@endif
    </header>

    <div id="amain" class="main-row">

            @yield('content')

    </div>

    <footer class="arow">
        @include('includes.footer')
    </footer>

</div>
</body>
</html>