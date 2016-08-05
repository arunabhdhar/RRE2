@extends('adminlayouts.default')
@section('content')
<!-- BEGIN LOGIN -->
<div id="login">
    <!-- BEGIN LOGIN FORM -->
    <form id="loginform" class="form-vertical no-padding no-margin" method="post" action="/admin/loginsubmit">
        <div class="messagebox">
        @if($errors->has())
            @foreach ($errors->all() as $error)
            <div class="errormsg">{{ $error }}</div>
            @endforeach
        @endif
        
        @if(Session::has('flash_errmessage'))
            <div class="errormsg">
                {{ Session::get('flash_errmessage') }}
            </div>
        @endif
        </div>
        {!! csrf_field() !!}
        <div class="lock">
            <i class="icon-lock"></i>
        </div>
        <div class="control-wrap">
            <h4>User Login</h4>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-user"></i></span><input id="user_name" name="user_name" type="text" placeholder="Username" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-key"></i></span><input id="user_password" name="user_password" type="password" placeholder="Password" />
                    </div>
                    <div class="mtop10">
                        <div class="block-hint pull-left small">
                            <input type="checkbox" id=""> Remember Me
                        </div>
                        <div class="block-hint pull-right">
                            <a href="javascript:;" class="" id="forget-password">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="clearfix space5"></div>
                </div>

            </div>
        </div>

        <input type="submit" id="login-btn" class="btn btn-block login-btn" value="Login" />
    </form>
    <!-- END LOGIN FORM -->        
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form id="forgotform" class="form-vertical no-padding no-margin hide" action="/admin">
        <p class="center">Enter your e-mail address below to reset your password.</p>
        <div class="control-group">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-envelope"></i></span><input id="input-email" type="text" placeholder="Email"  />
                </div>
            </div>
            <div class="space20"></div>
        </div>
        <input type="button" id="forget-btn" class="btn btn-block login-btn" value="Submit" />
    </form>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<!-- END LOGIN -->
@stop