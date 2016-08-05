@extends('adminlayouts.default')
@section('content')
<script type="text/javascript">
jQuery(document).ready(function(){
    // ADD APPS VALIDATION
    jQuery("#changepasswrd").validationEngine();
});
</script>
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
                        Change Password
                        <small>Flashfind Change Passwords</small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                        </li>
                        <li>
                            <a href="#">Admin</a> <span class="divider">&nbsp;</span>
                        </li>
                        <li><a href="#">Change Password</a><span class="divider-last">&nbsp;</span></li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN SAMPLE FORM widget-->   
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>Change Password</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- a href="javascript:;" class="icon-remove"></a -->
                            </span>
                        </div>
                        <div class="widget-body form">
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
                            <form action="/admin/changepasswordsubmit" class="form-horizontal" id="changepasswrd" name="changepasswrd" method="post">
                                
                                {!! csrf_field() !!}
                                <div class="control-group">
                                    <label class="control-label">Old Password</label>
                                    <div class="controls">
                                        <input type="text" name="old_password" id="old_password" class="validate[required] span6" />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">New Password</label>
                                    <div class="controls">
                                        <input type="password" name="new_password" id="new_password" class="validate[required] span6 " />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Confirm New Password</label>
                                    <div class="controls">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="validate[required,equals[new_password]] span6 " />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="button" class="btn">Cancel</button>
                                </div>
                            </form>
                            <!-- END FORM-->           
                        </div>
                    </div>
                    <!-- END SAMPLE FORM widget-->
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