@extends('layouts.default')
@section('content')

<table align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td height="300px">
            <table width="900px" align="center" cellpadding="20" cellspacing="0" border="0" class="formcontent">
                <tr> 
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td><h1>Photo</h1></td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="5" cellspacing="0" border="0">
                                        <tr>
                                            <td>
                                                @if(file_exists(public_path(Auth::user()->user_image)) && Auth::user()->user_image != "") 
                                                <image src="{{ Auth::user()->user_image }}" width="150px" />
                                                @else
                                                    @if( Auth::user()->user_gender == 2 ) 
                                                        <image src="/images/profile/profile-female.png" width="150px" />
                                                    @else
                                                        <image src="/images/profile/profile-male.png" width="150px" />
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>   
                      
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td><h1>Contact Info</h1></td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="5" cellspacing="0" border="0">
                                        <tr>
                                            <td><b>Full Name : </b></td>
                                            <td>{{ Auth::user()->user_full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Email : </b></td>
                                            <td>{{ Auth::user()->user_email }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>City : </b></td>
                                            <td>{{ Auth::user()->user_city }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Country : </b></td>
                                            <td>{{ Auth::user()->user_country }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>    
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td><h1>About us</h1></td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td>{{ Auth::user()->user_about }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>    
                </tr>
                <tr><td height="50px">&nbsp;</td></tr>
                <tr> 
                    <td align="center" colspan="3">
                        <h1>Photos</h1>
                    </td>    
                </tr>
            </table>
        </td>        
    </tr>
</table>
@stop