<?php
$menuval = substr(Route::currentRouteAction(), 0, (strpos(Route::currentRouteAction(), '@')) );

?>
<ul class="sidebar-menu">
    <li class="has-sub <?=strpos($menuval, "UserController")?"active":""?>">


        <a href="javascript:;" class="">
            <span class="icon-box"> <i class="icon-dashboard"></i></span> Manage Users
            <span class="arrow"></span>
        </a>
        <ul class="sub">
            <li class="active"><a class="" href="/admin/user">User List</a></li>
        </ul>
    </li>
    <li class="has-sub <?=strpos($menuval, "InterestController")?"active":""?>">
        <a href="javascript:;" class="">
            <span class="icon-box"> <i class="icon-dashboard"></i></span> Manage Interest
            <span class="arrow"></span>
        </a>
        <ul class="sub">
            <li class="active"><a class="" href="/admin/interest">Interest List</a></li>
        </ul>
    </li>
</ul>