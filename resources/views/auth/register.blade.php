@extends('layouts.default')
@section('content')

<script type="text/javascript">
    $(function() {
        $('#user_dob').datepick();
    });
    jQuery(document).ready(function() {
        // ADD APPS VALIDATION
        jQuery("#userregfrm").validationEngine();
    });

    $("#user_image").change(function() {
        readURL(this, 'user_icon_img', 'userimageblock');
    });

//PLAY AND PAUSE APPS
    function getCityOnCountrySelect(country_id) {
        $.ajax({
            type: "GET",
            url: "/country/countryval",
            data: "posttime=<?php echo time(); ?>&countryid=" + country_id,
            success: function(data) {
                if (data != "") {
                    if (data == "Not-Done") {
                        jAlert("City not available for this country", "");
                        return false;
                    } else if (data == "Blank") {
                        jAlert("Please select country first.!", "");
                        return false;
                    } else {
                        $("#cityblock").html(data);
                    }
                }
            }
        });

    }


</script>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 cBusiness text-center">
            <h3>Create an Account</h3>
            

            <div class="login-page">
                <div class="form">
                    <form class="register-form" action="/auth/register" name="userregfrm" id="userregfrm" enctype="multipart/form-data" method="POST"> 
                    <div class="profile-avtar">
                        <input type="file" id="user_image" name="user_image" style="display:none" onchange="calltype(this, 'user_icon_img', 'userimageblock');"  style="border:none; background:#fff;" />
                        <img id="user_icon_img" class="upload_div" style="display:none;"  src="#" alt="your image" onclick="img_uploade('user_image')" />
                        <img src="{{ asset('images/profile-pic.png') }}" alt="" name="userimageblock" id="userimageblock" onclick="img_uploade('user_image')" style="display:block; cursor: pointer" />
                    </div>
                    
                        <div class="messagebox">
                            @if($errors->has())
                            @foreach ($errors->all() as $error)
                            <div class="errormsg">{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>
                        {!! csrf_field() !!}
                        <input id="user_full_name" name="user_full_name" type="text" class="validate[required,maxSize[100]] field-1" value="{{ old('user_full_name') }}" placeholder="Full Name*" />
                        <input id="user_email" name="user_email" type="email" class="validate[required,custom[email]] field-1" value="{{ old('user_email') }}" placeholder="Email*"/>
                        <input id="user_dob" name="user_dob" readonly="" type="text" class="field-1" value="{{ old('user_dob') }}" placeholder="DD-MM-YYYY"/>
                        <label>
                            <span class="wd-30">Gender :</span> 
                            <span class="wd-70">
                                <input type="radio" name="user_gender" id="user_gender1" class="validate[required]" value="1"> Male 
                                <input type="radio" name="user_gender" id="user_gender2" class="validate[required]" value="2"> Female
                            </span>
                        </label>
                        <input id="user_password" name="user_password" type="password" class="validate[required,minSize[6]] field-1" placeholder="Password*"/>
            <input id="user_password_confirmation" name="user_password_confirmation" type="password" class="validate[required,equals[user_password],minSize[6]] field-1" placeholder="Confirm Password*"/>
                        
                        <input type="hidden" name="hduserlat" id="hduserlat" value="" />
                        <input type="hidden" name="hduserlon" id="hduserlon" value="" />
                        <input type="submit" value="Registration" class="btn">
                        <p class="message">
                            <span class="pull-left"><a href="/auth/login">Login</a> Here.</span>
                            <span class="pull-right"><a href="/password/email">Forgot Passowrd?</a></span></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop