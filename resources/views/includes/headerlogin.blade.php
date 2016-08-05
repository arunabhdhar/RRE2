<!--header-->

<script> 

$('#user_search').live('keypress', function(e) {
    var p = e.which;
    if (p == 13) {
        var user_search = $( "#user_search" ).val();
        window.location.href = "/searchuser/searcheduser?searchkey="+user_search;
    }
});


$(document).ready(function(){
    $("#user_search").keyup(function(){
        $.ajax({
        type: "GET",
        url: "/index/searcheduserauto",
        data:'searchkey='+$(this).val(),
        beforeSend: function(){
                $("#user_search").css("background","#FFF url(/images/LoaderIcon.gif) no-repeat 239px");
        },
        success: function(data){
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#user_search").css("background","url(/images/search_03.png) no-repeat #d3dee0 center right 12px");
        }
        });
    });
});

function selectCountry(val) {
    $("#user_search").val(val);
    var user_search = val;
    window.location.href = "/searchuser/searcheduser?searchkey="+user_search;
        
    $("#suggesstion-box").hide();
}
</script>
<style type="text/css">
    .badge1 {
            position:relative;
    }
    .badge1[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:-10px;
            right:-10px;
            font-size:.7em;
            background:#DD202F;
            color:white;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #DD202F;
    }
    
    #country-list{float:left;list-style:none;margin:0;padding:0;width:190px;}
    #country-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
    #country-list li:hover{background:#F0F0F0;}

</style>
<!--header-->
<div class="header">
    <div class="wrapper">
        <div class="prof_menu">
            <ul>
                <li><a href="/user/profile"></a></li>
                <li class="prof_home"><a href="/user/home"></a></li>
            </ul>
        </div>
        <a href="#" class="innerlogo"> <img src="{{ asset('images/inner-logo.png') }}" alt="" /> </a>
        <div class="setting_menu">
            <ul>
                <li><a href="/user/notification" class="<?=(App\Models\Userfriend::getAllFriendNotifyCount(Auth::user()->user_id))>0?"badge1":""?>" data-badge="<?=(App\Models\Userfriend::getAllFriendNotifyCount(Auth::user()->user_id))?>"></a></li>
                <li class="ico_msg"><a href="/user/inbox" class="<?=(App\Models\Userchat::getAllPendingMessagesCount(Auth::user()->user_id))>0?"badge1":""?> data-badge="<?=(App\Models\Userchat::getAllPendingMessagesCount(Auth::user()->user_id))?>"></a></li>
                <li class="ico_setting"><a href="/user/setting"></a></li>
            </ul>
        </div>
        <div class="sec-login">
            <div style="float:left">
                <input type="text" name="user_search" id="user_search" placeholder="Search" class="search" value="<?php if (isset($_REQUEST['searchkey'])) {
    echo $_REQUEST['searchkey'] != "" ? $_REQUEST['searchkey'] : "";} ?>" />
                <div id="suggesstion-box" style="z-index: 99999; position: absolute;"></div>
            </div>
            <div style="float:left; padding: 8px 0px 0px">
                <!--a href="/auth/logout" class="login">Logout</a> <a href="/user/changepassword" class="register">Change</a -->
                <a href="/searchuser/searchednearby" class="login">Search Near By</a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--/header-->