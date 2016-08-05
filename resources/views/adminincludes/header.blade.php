<!-- END HEAD -->
<!-- BEGIN BODY -->

@if(!Session::get('adminDetailSession'))
<body id="login-body">
    <div class="login-header">
        <!-- BEGIN LOGO -->
        <div id="logo" class="center">
            <img src="{{ asset('adminstyle/img/logo.png') }}" alt="logo" width="121px" class="center" />
        </div>
        <!-- END LOGO -->
    </div>
@else
    @include('adminincludes.headerlogin')
@endif
    
