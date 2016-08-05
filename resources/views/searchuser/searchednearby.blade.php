@extends('layouts.default')
@section('content')
<script type="text/javascript">
jQuery(document).ready(function(){
	// ADD APPS VALIDATION
    jQuery("#searchednearby").validationEngine();
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
        <div class="search-nearby">
            <h2>Search Nearby</h2>
            <h4>Search Users</h4>
            <div class="search-logo"> <img src="{{ asset('images/search-nearby.png') }}" alt="" /> </div>
            <div class="search-options">
                <form action="/searchuser/searchnearbyres" name="searchednearby" id="searchednearby" method="post">
                    {!! csrf_field() !!}
                    <input type="radio" name="user_gender" value="1" id="radio-1" class="radio" />
                    <label for="radio-1" onclick="">Male</label>
                    <input type="radio" name="user_gender" value="2" id="radio-2" class="radio" />
                    <label for="radio-2">Female</label>
                    <input type="radio" name="user_gender" value="" id="radio-3" class="radio" />
                    <label for="radio-3">Both</label><br>
                    <a href="javascript:;" class="interest_btn">Select Interests</a><br />
                    <div class="interests_selections">
                        <span><input type="checkbox" name="user_interest_all" id="user_interest_all" value="1">All</span>
                        @foreach( $interest_list as $interestval )
                        <span><input type="checkbox" name="user_interest[]" value="{{ $interestval->interest_id }}">{{ $interestval->interest_name }}</span>
                        @endforeach
                        <div class="clear"></div>
                    </div>
                    <input type="hidden" name="hduserlat" id="hduserlat" value="" />
                    <input type="hidden" name="hduserlon" id="hduserlon" value="" />
                    <input type="submit" name="search" value="Find" class="search-btn" />
                </form>
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