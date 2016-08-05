<input type="text" name="search" value="" placeholder="Search" class="field_2" />
<ul>
    @if( $user_friend_list )
        <?php $i = 1 ?>
        @foreach( $user_friend_list as $user_friend_list_val)
            @if( $user_friend_list_val->sender_user_id == Auth::user()->user_id )
                <li <?php if(isset($selected_frnd_id)){ echo ($selected_frnd_id==$user_friend_list_val->recvr_user_id?"class='active'":""); }?>>
                    <a href="/user/chat/{{ encryptedUniqueId($user_friend_list_val->recvr_user_id) }}" style="text-decoration: none">
                        @if(file_exists(public_path($user_friend_list_val->recv_user_image)) && $user_friend_list_val->recv_user_image != "") 
                        <image src="{{ $user_friend_list_val->recv_user_image }}" alt="" width="58px" />
                        @else
                            @if( $user_friend_list_val->recv_user_gender == 2 ) 
                                <image src="/images/profile/profile-female.png" alt="" width="58px" />
                            @else
                                <image src="/images/profile/profile-male.png" alt="" width="58px" />
                            @endif
                        @endif
                        <h5>
                            {{ ucwords($user_friend_list_val->recv_user_full_name) }}
                            <span>
                                @if( $user_friend_list_val->recv_user_city != 0 )
                                {{ $user_friend_list_val->recv_city_name }},
                                @else
                                {{ $user_friend_list_val->recv_user_city_other }},
                                @endif
                                {{ $user_friend_list_val->recv_nicename }}
                            </span>
                        </h5>
                        <span class="new_msg">{{ App\Models\Userchat::getUnreadMessageCount($user_friend_list_val->recvr_user_id, $user_friend_list_val->sender_user_id) }}</span>
                    </a>
                </li>
            @else
                <li <?php if(isset($selected_frnd_id)){ echo ($selected_frnd_id==$user_friend_list_val->sender_user_id?"class='active'":""); }?>>
                    <a href="/user/chat/{{ encryptedUniqueId($user_friend_list_val->sender_user_id) }}" style="text-decoration: none">
                        @if(file_exists(public_path($user_friend_list_val->sender_user_image)) && $user_friend_list_val->sender_user_image != "") 
                        <image src="{{ $user_friend_list_val->sender_user_image }}" alt="" width="58px" />
                        @else
                            @if( $user_friend_list_val->sender_user_gender == 2 ) 
                                <image src="/images/profile/profile-female.png" alt="" width="58px" />
                            @else
                                <image src="/images/profile/profile-male.png" alt="" width="58px" />
                            @endif
                        @endif
                        <h5>
                            {{ ucwords($user_friend_list_val->sender_user_full_name) }}
                            <span>
                                @if( $user_friend_list_val->sender_user_city != 0 )
                                {{ $user_friend_list_val->sender_city_name }},
                                @else
                                {{ $user_friend_list_val->sender_user_city_other }},
                                @endif
                                {{ $user_friend_list_val->sender_nicename }}
                            </span>
                        </h5>
                        <span class="new_msg">{{ App\Models\Userchat::getUnreadMessageCount($user_friend_list_val->sender_user_id, $user_friend_list_val->recvr_user_id) }}</span>
                    </a>
                </li>
            @endif
        <?php $i++; ?>
        @endforeach
    @else
        No friends
    @endif
    <!-- li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
    <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
    <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li>
    <li><img src="{{ asset('images/image_02.png') }}" alt="" /><h5>John Doe<span>Miami, Florida</span></h5></li -->
</ul>
<div class="clear"></div>