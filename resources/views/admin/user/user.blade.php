@extends('adminlayouts.default')
@section('content')
<!-- BEGIN CONTAINER -->
<div id="container" class="row-fluid">
    <!-- BEGIN SIDEBAR -->
    <div id="sidebar" class="nav-collapse collapse">

        <div class="sidebar-toggler hidden-phone"></div>   

        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <div class="navbar-inverse">
            <form class="navbar-search visible-phone">
                <input type="text" class="search-query" placeholder="Search" />
            </form>
        </div>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
        <!-- BEGIN SIDEBAR MENU -->
        @include('adminincludes.sidebar')
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->  
    <div id="main-content">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN THEME CUSTOMIZER>
                    <div id="theme-change" class="hidden-phone">
                        <i class="icon-cogs"></i>
                        <span class="settings">
                            <span class="text">Theme:</span>
                            <span class="colors">
                                <span class="color-default" data-style="default"></span>
                                <span class="color-gray" data-style="gray"></span>
                                <span class="color-purple" data-style="purple"></span>
                                <span class="color-navy-blue" data-style="navy-blue"></span>
                            </span>
                        </span>
                    </div>
                    <!-- END THEME CUSTOMIZER-->
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        Manage User
                        <small>Flashfind Manage User</small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                        </li>
                        <li>
                            <a href="#">Admin</a> <span class="divider">&nbsp;</span>
                        </li>
                        <li><a href="#">Manage User</a><span class="divider-last">&nbsp;</span></li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>Managed User</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- a href="javascript:;" class="icon-remove"></a -->
                            </span>
                        </div>
                        <div class="widget-body">
                            <table class="table table-striped table-bordered" id="sample_1">
                                <thead>
                                    <tr>
                                        <th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                        <th>Username</th>
                                        <th class="hidden-phone">Email</th>
                                        <th class="hidden-phone">DOB</th>
                                        <th class="hidden-phone">Joined</th>
                                        <th class="hidden-phone"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( count($paginator) > 0 )
                                        @foreach( $paginator as $userdetail ) 
                                        <tr class="odd gradeX">
                                            <td><input type="checkbox" class="checkboxes" value="1" /></td>
                                            <td>{{ ucwords($userdetail->user_full_name) }}</td>
                                            <td class="hidden-phone"><a href="mailto:{{ $userdetail->user_email }}">{{ $userdetail->user_email }}</a></td>
                                            <td class="hidden-phone">{{ date('j, F Y', strtotime($userdetail->user_dob)) }}</td>
                                            <td class="center hidden-phone">{{ date('j, F Y', strtotime($userdetail->created_at)) }}</td>
                                            <td class="hidden-phone"><span class="label label-success">Approved</span></td>
                                        </tr>
                                        @endforeach
                                    @else
                                        Data not found
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->         
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->  
</div>
<!-- END CONTAINER -->
@stop