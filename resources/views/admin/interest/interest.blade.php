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
                        Manage Interest
                        <small>Flashfind Manage Interest</small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                        </li>
                        <li>
                            <a href="#">Admin</a> <span class="divider">&nbsp;</span>
                        </li>
                        <li><a href="#">Manage Interest</a><span class="divider-last">&nbsp;</span></li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
               
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <div style="padding: 0px 0px 10px 0px"><button class="btn" type="button" onclick="location.href = '/admin/interest/add';">Add Interest</button></div>
                    
                    <div class="messagebox">
                        @if($errors->has())
                            @foreach ($errors->all() as $error)
                            <div class="errormsg">{{ $error }}</div>
                            @endforeach
                        @endif
                        @if(Session::has('flash_successmessage'))
                            <div class="successmsg">
                                {{ Session::get('flash_successmessage') }}
                            </div>
                        @endif
                        @if(Session::has('flash_errmessage'))
                            <div class="errormsg">
                                {{ Session::get('flash_errmessage') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>Managed Interest</h4>
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
                                        <th>Interest Name</th>
                                        <th class="hidden-phone">Created On</th>
                                        <th class="hidden-phone"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( count($paginator) > 0 )
                                        @foreach( $paginator as $interestdetail ) 
                                        <tr class="odd gradeX">
                                            <td><input type="checkbox" class="checkboxes" value="1" /></td>
                                            <td>{{ ucwords($interestdetail->interest_name) }}</td>
                                            <td class="center hidden-phone">{{ date('j, F Y', strtotime($interestdetail->created_at)) }}</td>
                                            <td class="hidden-phone">
                                                <button class="btn btn-primary" onclick="location.href = '/admin/interest/edit?intrestid=<?=$interestdetail->interest_id?>';"><i class="icon-pencil icon-white"></i> Edit</button>
                                                <button class="btn btn-danger" onclick="location.href = '/admin/interest/deleteinterest?intrestid=<?=$interestdetail->interest_id?>';"><i class="icon-remove icon-white"></i> Delete</button>
                                            </td>
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