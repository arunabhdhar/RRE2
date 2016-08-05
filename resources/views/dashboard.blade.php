@extends('layouts.default')
@section('content')

<div class="banner head-bg prof_dashban">
    <div class="inner-wrap">
        <a href="#" class="signup-btn"><img src="{{ asset('images/singup.png') }}" alt=""></a>
    </div>
</div>
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
                <h4>{{ Auth::user()->user_full_name }}<span>{{ Auth::user()->user_city }}, {{ Auth::user()->user_country }}</span></h4>
            </div>

            <input type="text" name="search" value="" placeholder="Search" class="field_2" />
            <ul>
                <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
                <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
                <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
                <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
                <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="dashboard_screen">
            <h2>Dashboard</h2>
            <div class="screen_wrap">
                <div class="inner_screen">
                    <h3></h3>
                    <div class="notifications_area">
                        <ul class="noti">
                            <!-- li><a href="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="accept_button"></a><a href="#" class="decline_button"></a></li>
                            <li><a hr!-- ef="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="button">Read Message</a></li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.</li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="accept_button"></a><a href="#" class="decline_button"></a></li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="button">View Profile</a></li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.</li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="button">View Profile</a></li>
                            <li><a href="#" class="noti_cross"></a>John Doe sent a connect request.<a href="#" class="button">View User</a></li -->
                        </ul>
                    </div>
                </div>
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

@stop