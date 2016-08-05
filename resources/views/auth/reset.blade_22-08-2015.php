@extends('layouts.default')
@section('content')

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formcontent">
    <tr>
        <td align="center">
            <form  action="/password/reset" method="POST"> 
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr><td>&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><h1> Login Here </h1> </td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2">{!! csrf_field() !!}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input id="user_email" name="user_email" required="required" type="text" value="{{ old('user_email') }}" placeholder="Please enter you email address"/></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input id="user_password" name="user_password" required="required" type="password" placeholder="Please enter you password" /> </td>
                    </tr>
                    <tr>
                        <td>Confirm Password</td>
                        <td><input id="user_password_confirmation" name="user_password_confirmation" required="required" type="password" placeholder="Please re-enter you password" /> </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <input type="hidden" name="token" value="{{ $token }}">
                        <td colspan="2"><input type="submit" value="Reset Password" style="width: 100px; height: 30px;" /> </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

@stop