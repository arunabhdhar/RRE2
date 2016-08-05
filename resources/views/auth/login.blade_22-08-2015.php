@extends('layouts.default')
@section('content')

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formcontent">
    <tr>
    <link href="../../../public/css/style.css" rel="stylesheet" type="text/css"/>
        <td align="center">
            <form  action="/auth/login" method="POST"> 
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr><td>&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><h1> Login Here </h1> </td></tr>
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
                        <td>Email</td>
                        <td><input id="user_email" name="user_email" required="required" type="text" value="{{ old('user_email') }}" placeholder="Please enter you email address"/></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input id="user_password" name="user_password" required="required" type="password" placeholder="Please enter you password" /> </td>
                    </tr>
                    <tr>
                        <td>Keep me logged in</td>
                        <td><input type="checkbox" name="remember" id="loginkeeping" value="loginkeeping" /> </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><a href="/password/email">Forgot Password</a></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Login" style="width: 100px; height: 30px;" /> </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2">Not a member yet ?<a href="/auth/register">&nbsp;&nbsp;Join us</a></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

@stop