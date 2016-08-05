<!-- BEGIN FOOTER -->
<div id="footer">
    2013 &copy; Flashfind Admin.
    <div class="span pull-right">
        <span class="go-top"><i class="icon-arrow-up"></i></span>
    </div>
</div>
   <!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS -->
<script src="{{ asset('adminstyle/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('adminstyle/js/jquery.blockui.js') }}"></script>
<script src="{{ asset('adminstyle/js/scripts.js') }}"></script>
<script>
    jQuery(document).ready(function() {
        App.initLogin();
    });
</script>
<!-- END JAVASCRIPTS -->

<!-- BEGIN JAVASCRIPTS -->
<!-- ie8 fixes -->
<!--[if lt IE 9]>
<script src="{{ asset('adminstyle/js/excanvas.js') }}"></script>
<script src="{{ asset('adminstyle/js/respond.js') }}"></script>
<![endif]-->
<script type="text/javascript" src="{{ asset('adminstyle/assets/chosen-bootstrap/chosen/chosen.jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminstyle/assets/uniform/jquery.uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminstyle/assets/data-tables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminstyle/assets/data-tables/DT_bootstrap.js') }}"></script>
<script>
        jQuery(document).ready(function() {
                // initiate layout and plugins
                App.init();
        });
</script>
<!-- END JAVASCRIPTS -->