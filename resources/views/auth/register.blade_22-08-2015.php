@extends('layouts.default')
@section('content')

<script type="text/javascript">
$(function() {
    $('#user_dob').datepick();
  //$('#end_date').datepick();
  //$('#inlineDatepicker').datepick({onSelect: showDate});
});
</script>
<!-- resources/views/auth/register.blade.php -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formcontent">
    <tr>
        <td align="center">
            <form  action="/auth/register" method="POST"> 
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr><td>&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><h1> Registration </h1> </td></tr>
                    <tr>
                        <td class="messagebox" colspan="2">
                            @if($errors->has())
                                @foreach ($errors->all() as $error)
                                <div class="errormsg">{{ $error }}</div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">{!! csrf_field() !!}</td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td><input id="user_full_name" name="user_full_name" required="required" type="text" value="{{ old('user_full_name') }}" placeholder="Please enter your full name" /></td>
                    </tr>
                    <a href="../../../database/factories/ModelFactory.php"></a>
                    <tr>
                        <td>Email</td>
                        <td><input id="user_email" name="user_email" required="required" type="email" value="{{ old('user_email') }}" placeholder="Please enter your email address"/> </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input id="user_password" name="user_password" required="required" type="password" placeholder="Please enter your password"/></td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td><input id="user_dob" name="user_dob" readonly="" required="required" type="text" value="{{ old('user_dob') }}" placeholder="25-03-1986"/> </td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><input id="user_city" name="user_city" required="required" type="text" value="{{ old('user_city') }}" placeholder="Please enter your city name"/></td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td><input id="user_country" name="user_country" required="required" type="text" value="{{ old('user_country') }}" placeholder="Please enter your country name"/></td>
                    </tr>
                    <tr>
                        <td>About</td>
                        <td><input id="user_about" name="user_about" required="required" type="text" value="{{ old('user_about') }}" placeholder="Please write something for yourself"/></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="radio" name="user_gender" id="user_gender" value="1">&nbsp;Male
                            &nbsp;&nbsp;
                            <input type="radio" name="user_gender" id="user_gender" value="2">&nbsp;Female
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Registration" style="width: 100px; height: 30px;" /> </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2">Already a member ?<a href="/auth/login">&nbsp;&nbsp;Go and log in</a></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
@stop