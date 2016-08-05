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
                    <!-- BEGIN SAMPLE FORMPORTLET-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>Add Interest</h4>
                       
                        </div>
                        <div class="widget-body">
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
                            <!-- BEGIN FORM-->
                            <form action="/admin/interest/addinterest" class="form-horizontal" method="post">
                                {!! csrf_field() !!}
                                <div class="control-group">
                                    <label class="control-label">Interest Name</label>
                                    <div class="controls">
                                        <input type="text" name="interest_name" id="interest_name" placeholder="Interest name" class="input-large" tabindex="1" />
                                        <span class="help-inline">Ex: Music</span>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="control-label">Status</label>
                                    <div class="controls">
                                        <select class="input-small m-wrap" tabindex="2" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                    <button type="button" class="btn" onclick="location.href = '/admin/interest';"><i class=" icon-remove"></i> Cancel</button>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
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