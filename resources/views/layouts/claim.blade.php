<!-- Author:- Anshul Agrawal -->
<!-- anshul.agrawal889@gmail.com -->
<!-- 9720044889 -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Claim</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('bsb/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Custom Css -->
    <link href="{{ asset('bsb/css/materialize.css') }}" rel="stylesheet">
    <link href="{{ asset('bsb/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('bsb/css/themes/theme-cyan.css') }}" rel="stylesheet" />
    
    <!-- Jquery Core Js -->
    <script src="{{ asset('bsb/plugins/jquery/jquery.min.js') }}"></script>
    
</head>

<body class="theme-cyan">
    
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header" style="display:inline;text-align: center;">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0); " class="bars"></a>
                <div class="navbar-brand">
                    <img src="{{ asset('bsb/images/logo.png')}}" style="height: 45px;margin-top: -11px;display: inline-block;" alt="PLS Automobile Services Pvt. Ltd.">
                    <h4 style="display:inline;text-align: center;"> PLS Automobile Services Pvt. Ltd.</h4></a>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::user()->user_type == 1)
                    <li>
                        <a href="{{ url('dashboard')}}" title="Back to dashboard"><i class="material-icons">dashboard</i></a>
                    </li>       
                    @endif
                    
                    <li>
                        <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="logout">
                            <i class="material-icons" style="color: black;font-size: 27px;">input</i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <br>
    <br>
	<section class="container-fluid">
	@yield('content')
        <div class="block-header" style="text-align: center;">
            <img src="{{ asset('bsb/images/logo.png')}}" style="height: 30px;" alt="PLS Automobile Services Pvt. Ltd.">
            <h2> PLS Automobile Services Pvt. Ltd.</h2>
        </div>
    </section> 

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('bsb/plugins/bootstrap/js/bootstrap.js') }}"></script>
    
    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('bsb/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('bsb/js/admin.js') }}"></script>
    <!-- <script src="{{ asset('bsb/js/pages/forms/form-validation.js') }}"></script> -->


</body>

</html>