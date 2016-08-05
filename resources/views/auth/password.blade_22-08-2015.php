@extends('layouts.default')
@section('content')

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="formcontent">
    <tr>
        <td align="center">
            <form  action="/password/email" method="POST"> 
                <table cellpadding="5" cellspacing="0" border="0">
                    <tr><td>&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><h1> Forgot Password </h1> </td></tr>
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
                        <td><input id="user_email" name="user_email" required="required" value="{{ old('user_email') }}" type="text" placeholder="Please enter you email address"/></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Send Password Reset Link" style="width: 200px; height: 30px;" /> </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

@stop