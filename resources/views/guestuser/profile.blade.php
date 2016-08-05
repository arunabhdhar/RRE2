@extends('layouts.default')
@section('content')
<script type="text/javascript">
$(function() {
    $('#user_dob').datepick();
  //$('#end_date').datepick();
  //$('#inlineDatepicker').datepick({onSelect: showDate});
});

function enableConnect(){
    $('#connectmsgblock').css("display", "block");
}
//SEND FRIEND REQUEST
function sendFriendRequest( guest_id, frnd_req_status, div_block, pos, friend_block ){	
    
    var connect_message = $("#connect_message").val();
    //alert(connect_message);
    $.ajax({
        type: "GET",
        url: "/user/friendrequest",
        data: "posttime=<?php echo time(); ?>&guestid=" + guest_id + "&frnd_request_status=" + frnd_req_status + "&friend_block=" + friend_block + "&connect_message=" + connect_message,
        success: function(data) {          
            //alert(data);
            if (data != "") {
                if (data == "Login-Please") {
                    $("#frnd_req_block").html("<div class='errormsg'>Please logged in to send friend request</div>");
                    return false;
                } else if (data == "Request-Pending") {
                    $("#frnd_req_block").html("<div class='successmsg'>Your friend request in pending</div>");
                    return false;
                } else if (data == "Delete-Request") {
                    $("#friend_req_block").html("");
                    $("#frnd_req_block").html("<div class='successmsg'>Pending request deleted successfully</div>");
                    //alert("<a href='#' class='button' onclick='sendFriendRequest("+guest_id+", 1, 'status_div_block', 1)'>Connect</a>");
                    var divdata = "<a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 1, 'status_div_block', 1)\">Connect</a>";
                    $("#friend_req_block").html(divdata);
                    return false;
                } else if (data == "Already-Friend") {
                    $("#frnd_req_block").html("<div class='successmsg'>You are already friend</div>");
                    //jAlert("You are alreadyfriend", "");
                    return false;
                } else if (data == "Not-Done") {
                    $("#frnd_req_block").html("<div class='errormsg'>Problem with server</div>");
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Friend-Block") {
                    $("#frnd_req_block").html("<div class='successmsg'>Friend blocked</div>");
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Request-Rejected") {
                    $("#frnd_req_block").html("<div class='successmsg'>Request rejected successfully</div>");
                    var divdata = "<a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 1, 'status_div_block', 1)\">Connect</a>";
                    $("#friend_req_block").html(divdata);
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Un-Friend") {
                    $("#frnd_req_block").html("<div class='successmsg'>Friend delete successfully from friend list</div>");
                    var divdata = "<a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 1, 'status_div_block', 1)\">Connect</a>";
                    $("#friend_req_block").html(divdata);
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Request-Accepted") {
                    $("#frnd_req_block").html("<div class='successmsg'>Request accepted successfully</div>");
                    var divdata = " <a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 2, 'status_div_block', 1, 1)\">Block</a><a href=\"/user/chat/<?=encryptedUniqueId($user->user_id)?>\" class=\"button\">Message</a><a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 5, 'status_div_block', 1)\">Un-Friend</a>";
                    $("#friend_req_block").html(divdata);
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Friend-UnBlocked") {
                    $("#frnd_req_block").html("<div class='successmsg'>Friend un-blocked successfully</div>");
                    var divdata = "<a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 1, 'status_div_block', 1)\">Connect</a>";
                    $("#friend_req_block").html(divdata);
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Friend-Blocked") {
                    $("#frnd_req_block").html("<div class='successmsg'>Friend blocked successfully</div>");
                    var divdata = " <a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 2, 'status_div_block', 1, 0)\">Un-Block</a>";
                    $("#friend_req_block").html(divdata);
                    //jAlert("Your friend request not send", "");
                    return false;
                } else if (data == "Friend-Request-Added") {
                    $('#connectmsgblock').css("display", "none");
                    //jAlert("Friend request send successfully!", "");
                    $("#frnd_req_block").html("<div class='successmsg'>Connect request sent sccessfully</div>");
                    var divdata = "<a href=\"#\" class=\"button\" onclick=\"sendFriendRequest("+guest_id+", 2, 'status_div_block', 1)\">Cancel Request</a>";
                    $("#friend_req_block").html(divdata);
                    return true;
                }
            }
        }
    });   

}


</script>
@include('includes.createaccount')
<div class="wrapper">

    <div class="prof_dash">
        <div class="prof_col1">
            <div class="profile_display">
                <div class="profile_pic">
                    @if(file_exists(public_path(Auth::user()->user_image)) && Auth::user()->user_image != "") 
                    <image src="{{ Auth::user()->user_image }}" width="150px" />
                    @else
                        @if( Auth::user()->user_gender == 2 ) 
                            <image src="/images/profile/profile-female.png" />
                        @else
                            <image src="/images/profile/profile-male.png" />
                        @endif
                    @endif
                </div>
                        <h4>{{ Auth::user()->user_full_name }}<span>
                    @if( $user_details->user_city != 0 )
                    {{ $user_details->city_name }},
                    @else
                    {{ $user_details->user_city_other }},
                    @endif
                    {{ $user_details->nicename }}
                    </span></h4>
            </div>

              @include('includes.userfriendleftbar')
        </div>
        <div class="dashboard_screen user_screen">

            <div class="screen_wrap">
                <div class="user_inner">
                    <div class="messagebox">
                        @if($errors->has())
                            @foreach ($errors->all() as $error)
                            <div class="errormsg">{{ $error }}</div>
                            @endforeach
                        @endif
                        @if(Session::has('flash_message'))
                            <div class="successmsg">
                                {{ Session::get('flash_message') }}
                            </div>
                        @endif
                    </div>
                    <div class="user_col1">
                        @if(file_exists(public_path($user->user_image)) && $user->user_image != "") 
                        <image src="{{ $user->user_image }}" width="150px" />
                        @else
                            @if( $user->user_gender == 2 ) 
                                <image src="/images/profile/profile-female.png" width="150px" />
                            @else
                                <image src="/images/profile/profile-male.png" width="150px" />
                            @endif
                        @endif
                    </div>
                    <div class="user_col2">
                        <h2>{{ $user->user_full_name }}<br><span></span><span>{{ calculateAge($user->user_dob) }} years old</span>
                            <div style="float: right; margin: -48px 0px 0px 0px; font-size: 14px;"><a href="javascript:history.go(-1);">Back</a></div></h2>
                        <ul>
                            @if($Friend_Status == "Friend")
                            <li><a href="#">{{ $user->user_email }}</a></li>
                            <li class="user_phone"><?=$user->user_contact_number!=""?$user->user_contact_number:""?></li>
                            @endif
                            <li class="user_ads">
                                @if( $user->user_city != 0 )
                                {{ $user->city_name }},
                                @else
                                {{ $user->user_city_other }},
                                @endif
                                {{ $user->nicename }}
                            </li>
                            <li class="user_ads">
                                @if( $user->user_relationship_status == 1 )
                                    Single
                                @elseif( $user->user_relationship_status == 2 )
                                    Married
                                @elseif( $user->user_relationship_status == 3 )
                                    It's Complicated
                                @elseif( $user->user_relationship_status == 4 )
                                    In Relationship
                                @endif
                            </li>
                        </ul>
                        <div class="messagebox" id="frnd_req_block"></div>
                        <div id="connectmsgblock" style="padding: 5px 0px; display: none">
                            <input style="height: 28px; border-radius: 5px; padding: 0px 5px" type="text" name="connect_message" placeholder="Type your message here" id="connect_message" value="" />
                            <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 1, 'status_div_block', 1, 0)">Send Connect</a>
                        </div>
                        @if(Auth::check())
                        <div id="friend_req_block">
                            @if($Friend_Status == "Friend")
                                <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 6, 'status_div_block', 1, 1)">Block</a>
                            @elseif($Friend_Status == "Blocked")
                                @if( $user_blocked_status == 1 )
                                    <a href="#" class="button">Blocked</a>
                                @else
                                    <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 6, 'status_div_block', 1, 0)">Un-Block</a>
                                @endif
                            @endif
                            @if($Friend_Status == "Friend")
                                <a href="/user/chat/{{ encryptedUniqueId($user->user_id) }}" class="button">Message</a>
                            @endif
                        
                            @if($Friend_Status == "")
                            <a href="javascript:void(0);" class="button" onclick="enableConnect()">Connect</a>
                            @else

                                @if( $Friend_Status == "Friend" )
                                    <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 5, 'status_div_block', 1, 0)">Un-Friend</a>
                                @elseif( $Friend_Status == "Request pending" )
                                    <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 2, 'status_div_block', 1, 0)">Cancel Request</a>
                                @elseif( $Friend_Status == "Accept request" )
                                    <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 3, 'status_div_block', 1, 0)">Accept Request</a>
                                    <a href="#" class="button" onclick="sendFriendRequest(<?=$user->user_id?>, 4, 'status_div_block', 1, 0)">Reject Request</a>
                                @endif
                            @endif
                        </div>
                        @endif
                        
                    </div>
                    <div class="clear"></div>
                </div>

            </div>

            <div class="user_about">
                <h2>About</h2>
                <p>
                    {{ $user->user_about }}
                </p>
            </div>
            
            <div class="user_about">
                <h2>Status</h2>
                <p>
                    @if( $user->user_profile_screen_status )
                        {{ $user->user_profile_screen_status }}
                    @else 
                        NA
                    @endif
                </p>
            </div>
        </div>

        <div class="prof_col3">
            <h2>FlashFind App</h2>
            <a href="#" class="google_store"></a>
            <a href="#" class="i_store"></a>
            <div class="widget-col"><img src="{{ asset('images/image_05.jpg') }}" alt="" /></div>
            <div class="widget-col2"><img src="{{ asset('images/image_04.jpg') }}" alt="" /></div>
            <div class="ad_here"><img src="{{ asset('images/image_08.jpg') }}" alt="" /></div>
        </div>
        <div class="clear"></div>
    </div>

</div>
@include('includes.recentuser')

@stop