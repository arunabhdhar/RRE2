@extends('layouts.default')
@section('content')
<script type="text/javascript">
    jQuery(document).ready(function() {
        // ADD APPS VALIDATION
        jQuery("#userforgotfrm").validationEngine();
    });
</script>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 cBusiness text-center">
            <h3>Forgot Passowrd?</h3>


            <div class="login-page-small">
                <div class="form">
                    <!-- div class="profile-avtar">
                        <img src="images/avtar.png" alt="">
                    </div -->
                    <form class="login-form" action="/password/email" name="userforgotfrm" id="userforgotfrm" method="POST">
                        <div class="messagebox">
                            @if($errors->has())
                            @foreach ($errors->all() as $error)
                            <div class="errormsg">{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>
                        {!! csrf_field() !!}
                        <input id="user_email" name="user_email" class="validate[required,custom[email]] field-1" value="{{ old('user_email') }}" type="text" placeholder="Email"/>
                        <input type="submit" name="login" value="Retrive Password" class="btn">
                        <p class="message">
                            <span class="pull-left"><a href="/auth/login">Login</a> Here.</span>
                            <span class="pull-right">Not registered? <a href="/auth/register">Create an account</a></span></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop