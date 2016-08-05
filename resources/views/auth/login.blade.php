@extends('layouts.default')
@section('content')
<script type="text/javascript">
jQuery(document).ready(function(){
	// ADD APPS VALIDATION
	jQuery("#userloginfrm").validationEngine();
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 cBusiness text-center">
            <h3>LogIn</h3>


            <div class="login-page-small">
                <div class="form">
                    <!--div class="profile-avtar">
                        <img src="{{ asset('images/avtar.png') }}" alt="">
                    </div -->
                    <form class="login-form" action="/auth/login" name="userloginfrm" id="userloginfrm" method="POST">
                        <div class="messagebox">
                        @if($errors->has())
                            @foreach ($errors->all() as $error)
                            <div class="errormsg">{{ $error }}</div>
                            @endforeach
                        @endif
                        </div>
                        {!! csrf_field() !!}
                        <input id="user_email" name="user_email" type="text" class="validate[required,custom[email]] field-1" value="{{ old('user_email') }}" placeholder="Email"/>
                        <input id="user_password" name="user_password" type="password" class="validate[required] field-1" placeholder="Password" />
                        <!-- Keep me logged in<input type="checkbox" name="remember" id="loginkeeping" value="loginkeeping" /> -->
                        <input type="submit" name="login" value="LOGIN" class="btn">
                        <p class="message">
                            <span class="pull-left">Not registered? <a href="/auth/register">Create an account</a></span>
                            <span class="pull-right"><a href="/password/email">Forgot Passowrd?</a></span></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop