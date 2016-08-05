@extends('layouts.default')
@section('content')
<script type="text/javascript">
jQuery(document).ready(function(){
	// ADD APPS VALIDATION
    jQuery("#userchatfrm").validationEngine();
});

setTimeout(function(){
   window.location.reload(1);
}, 180000);


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
                <h4>{{ Auth::user()->user_full_name }}
                    <span>
                        @if( $user_details->user_city != 0 )
                        {{ $user_details->city_name }},
                        @else
                        {{ $user_details->user_city_other }},
                        @endif
                        {{ $user_details->nicename }}
                    </span>
                </h4>
            </div>

            @include('includes.userfriendleftbar')
        </div>
        <div class="dashboard_screen">
            <h2>Results</h2>
            <div class="screen_wrap search_page">
                <div class="search_results">
                    @if( count($paginator) > 0 )
                    <?php $h = 0; ?>
                    <ul>
                        @foreach( $paginator as $userdetail ) 
                            <li>
                                <a href="/guestuser/profile/{{ $userdetail->user_id }}">
                                    @if(file_exists(public_path($userdetail->user_image)) && $userdetail->user_image != "") 
                                    <image src="{{ $userdetail->user_image }}" width="132px" />
                                    @else
                                        @if( $userdetail->user_gender == 2 ) 
                                        <image src="/images/profile/profile-female.png" width="132px" />
                                        @else
                                        <image src="/images/profile/profile-male.png" width="132px" />
                                        @endif
                                    @endif
                                </a>
                                <h3><a href="/guestuser/profile/{{ $userdetail->user_id }}">{{ ucwords($userdetail->user_full_name) }}</a></h3>
                                <!-- span>{{ $userdetail->user_email }}</span -->
                                <?php 
                                $check_already_friend = App\Models\Userfriend::checkAlreadyFriend(Auth::user()->user_id, $userdetail->user_id);
                                if($check_already_friend){
                                    $fetchDataObj = $check_already_friend[0];
                                    $friend_status = $fetchDataObj->friend_request_status;
                                    if( $friend_status == 1 )
                                    {
                                        if( $userdetail->user_email != "" ){
                                            echo "<span>".$userdetail->user_email."</span>";
                                        }
                                        
                                    }
                                }
                                ?>
                                <span>{{ date('j, F Y', strtotime($userdetail->user_dob)) }}</span>
                                <span>
                                    @if( $user->user_city != 0 )
                                    {{ $userdetail->city_name }},
                                    @else
                                    {{ $userdetail->user_city_other }},
                                    @endif
                                    {{ $userdetail->nicename }}
                                </span>
                                <div class="clear"></div>
                            </li>
                         @endforeach
                    </ul>
                    @else
                        Data not found
                    @endif
                    <div class="clear"></div></div>
                    @include('includes.paging')
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