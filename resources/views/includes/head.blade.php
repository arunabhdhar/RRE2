<title>Demo Site</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.css') }}">
<link rel='stylesheet' id='camera-css'  href="{{ asset('css/camera.css') }}" type='text/css' media='all'>

<link rel="stylesheet" type="text/css" href="{{ asset('css/slicknav.css') }}">
<link rel="stylesheet" href="{{ asset('css/prettyPhoto.css') }}" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">


<script type="text/javascript" src="{{ asset('js/jquery-1.8.3.min.js') }}"></script>

<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700|Open+Sans:700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="{{ asset('js/jquery.mobile.customized.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.easing.1.3.js') }}"></script> 
<script type="text/javascript" src="{{ asset('js/camera.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/myscript.js') }}"></script>
<script src="{{ asset('js/sorting.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.isotope.js') }}" type="text/javascript"></script>
<!--script type="text/javascript" src={{ asset('js/jquery.nav.js') }}"></script-->
<script src="{{ asset('js/functions.js') }}" type="text/javascript"></script>

<script>
jQuery(function() {
jQuery('#camera_wrap_1').camera({
transPeriod: 500,
time: 3000,
height: '490px',
thumbnails: false,
pagination: true,
playPause: false,
loader: false,
navigation: false,
hover: false
});
});
</script>

<!-- DATE PICKER START -->
<link rel="stylesheet" href="{{ asset('css/jquery.datepick.css') }}" type="text/css" media="screen" />
<script type="text/javascript" src="{{ asset('js/jquery.datepick.js') }}"></script>
<!-- DATE PICKER END -->

<script src="{{ asset('js/geo.js') }}"></script>

<!-- JQUERY VALIDATION START -->
<link rel="stylesheet" href="{{ asset('css/validationEngine.jquery.css') }}" type="text/css"/>
<script src="{{ asset('js/languages/jquery.validationEngine-en.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/jquery.validationEngine.js') }}" type="text/javascript" charset="utf-8"></script>
<!-- JQUERY VALIDATION END -->

<!-- JQUERY ALERT START -->
<link rel="stylesheet" href="{{ asset('css/jquery.alerts.css') }}" type="text/css"/>
<script src="{{ asset('js/jquery.alerts.js') }}" type="text/javascript" charset="utf-8"></script>
<!-- JQUERY ALERT END -->