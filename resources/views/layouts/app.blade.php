<!-- Author:- Anshul Agrawal -->
<!-- anshul.agrawal889@gmail.com -->
<!-- 9720044889 -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Expense</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('bsb/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('bsb/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('bsb/plugins/animate-css/animate.css') }}" rel="stylesheet" />

	<!-- Sweet Alert Css -->
    <link href="{{ asset('bsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
    
    <!-- Morris Chart Css-->
    <link href="{{ asset('bsb/plugins/morrisjs/morris.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('bsb/css/materialize.css') }}" rel="stylesheet">
    <link href="{{ asset('bsb/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('bsb/css/themes/theme-cyan.css') }}" rel="stylesheet" />
    
    <!-- Jquery Core Js -->
    <script src="{{ asset('bsb/plugins/jquery/jquery.min.js') }}"></script>
    
</head>

<body class="theme-cyan">
   <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header" style="display:inline;text-align: center;">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0); " class="bars"></a>
                <div class="navbar-brand">
                    <img src="{{ asset('bsb/images/logo.png')}}" style="height: 45px;margin-top: -11px;display: inline-block;" alt="PLS Automobile Services Pvt. Ltd.">
                    <h4 style="display:inline;text-align: center;"> The Book Planetz</h4></a>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!--<li class="pull-right"><a href="javascript:void(0); " class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>-->
                    <li><a href="{{ url('claim')}}" title="Accidental Vehicle Claim"><i class="material-icons">web</i></a></li>
                    <li><a href="{{ url('users/'.Auth::id().'/edit')}}" title="Profile"><i class="material-icons">person</i></a></li>
					<li><!--a href="javascript:void(0); "><i class="material-icons">input</i>Sign Out</a-->
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
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{ asset('bsb/images/user.png') }}" onerror="this.src='{{ asset('bsb/images/user.png')}}'" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth::user()->name}} ({{ auth::user()->email}})</div>
                    <div class="email">{{getFromID(auth::user()->workshop_id, 'workshops')}}(@if(auth::user()->user_type==1) Admin @elseif(auth::user()->user_type==3) Workshop Admin @elseif(auth::user()->user_type==5) Supervisor @else User @endif)</div>
                </div>
            </div>
            <!-- #User Info -->
            @include('layouts.publication-menu')
            <!-- Footer -->
            <div class="legal">
            	<div class="version">
                    Designed & Developed By
                </div>
                <div class="copyright">
                    <a href="http://techstreet.in/">Techstreet Solutions</a> &copy; {{date('Y')}} 
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
    
	<section class="content">
	    @yield('content')
    </section> 

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('bsb/plugins/bootstrap/js/bootstrap.js') }}"></script>
    
    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('bsb/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('bsb/plugins/node-waves/waves.js') }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('bsb/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('bsb/plugins/morrisjs/morris.js') }}"></script>

    <script src="{{ asset('bsb/plugins/chartjs/Chart.bundle.js') }}"></script>

    
    <!-- Custom Js -->
    <script src="{{ asset('bsb/js/admin.js') }}"></script>
    <!-- <script src="{{ asset('bsb/js/pages/forms/form-validation.js') }}"></script> -->


</body>

</html>