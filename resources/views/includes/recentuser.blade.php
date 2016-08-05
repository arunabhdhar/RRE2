<div class="new_members">
    <h3>Recently Joined</h3>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting</p>
    @if($recent_user)  
    <ul class="bxslider">
        @foreach( $recent_user as $userdetail )                            
            @if(file_exists(public_path($userdetail->user_image)) && $userdetail->user_image != "") 
            <li><a href="/guestuser/profile/{{ $userdetail->user_id }}"><image src="{{ $userdetail->user_image }}" width="100px" /></a></li>
            @else
                @if( $userdetail->user_gender == 2 ) 
                <li><a href="/guestuser/profile/{{ $userdetail->user_id }}"><image src="/images/profile/profile-female.png" width="100px" /></a></li>
                @else
                <li><a href="/guestuser/profile/{{ $userdetail->user_id }}"><image src="/images/profile/profile-male.png" width="100px" /></a></li>
                @endif
            @endif                 
        @endforeach
    </ul>
    @else
    Not Recent User
    @endif
</div>