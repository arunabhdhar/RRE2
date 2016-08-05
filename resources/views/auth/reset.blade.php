@extends('layouts.default')
@section('content')
<script type="text/javascript">
jQuery(document).ready(function(){
	// ADD APPS VALIDATION
	jQuery("#userresetfrm").validationEngine();
});
</script>
<div class="banner inner-ban">
    <div class="login-div">
        <h5>Reset Password</h5>
        <form action="/password/reset" name="userresetfrm" id="userresetfrm" method="POST">
            <div class="messagebox">
            @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="errormsg">{{ $error }}</div>
                @endforeach
            @endif
            </div>
            {!! csrf_field() !!}
            <input id="user_email" name="user_email" class="validate[required,custom[email]] field-1" type="text" value="{{ old('user_email') }}" placeholder="Email"/>
            <input id="user_password" name="user_password" class="validate[required,minSize[6]] field-1" type="password" placeholder="Password" />
            <input id="user_password_confirmation" name="user_password_confirmation" class="validate[required,minSize[6],equals[user_password]] field-1    " type="password" placeholder="Confirm Password" />
            <input type="hidden" name="token" value="{{ $token }}">
            <!-- Keep me logged in<input type="checkbox" name="remember" id="loginkeeping" value="loginkeeping" /> -->
            <input type="submit" name="resetpasswrd" value="Reset Password" class="btn">
        </form>
    </div>
</div>

@include('includes.recentuser')
@stop