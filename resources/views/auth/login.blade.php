@extends('layouts.basic')

@section('body')
<link href="https://fonts.googleapis.com/css?family=Raleway:300,500" rel="stylesheet" type="text/css">
        <style>
            html, body {
                background-color: #87ceeb;
                background: url({{ asset('welcome-bg.jpg')}});
                font-weight: 200;
                height: 70vh;
                margin: 0;
                text-align: center;
            }

            .full-height {
                height: 70vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .top-left {
                position: absolute;
                left: 10px;
                top: 18px;
            }

            .title {
                font-size: 50px;
                color: white;
                font-family: 'Raleway', sans-serif;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .login-box {
                margin: 20px;
            }
            .login-page {
                background-color: transparent !important;
            }
        </style>

<style> 
#panel, #flip {
    padding: 5px;
}

#panel {
    padding: 20px;
    display: none;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script> 
$(document).ready(function(){
    $("#login").click(function(){
        $("#panel").slideDown("slow");
        $("#flip").slideUp("slow");
    });

    // $("#panel").click(function(){
    //     $("#panel").slideUp("slow");
    //     $("#flip").slideDown("slow");
    // });
});
</script>

<body class="">
        <!-- <div class="flex-center position-ref full-height" id="flip">
            <div class="content">
                <div class="title m-b-md">
                    
                <img src="{{ asset('bsb/images/logo.png')}}" style="height: 120px;" alt="PLS Automobile Services Pvt. Ltd."><br>
                PLS Automobile Services Pvt. Ltd.<br>
                Expense Tracker
                </div>
                @if (Route::has('login'))
                    <div class="links">
                        @auth
                            <a class="btn btn-default btn-lg waves-effect" href="{{ url('/dashboard') }}"><b>Dashboard</b></a>
                            <a class="btn btn-default btn-lg waves-effect" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <b>Logout</b>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        @else
                            <button class="btn btn-default btn-lg waves-effect" id="login" ><b>Login</b></button>
                        @endauth
                    </div>
                @endif
            </div>
        </div> -->
<div class="login-page">
    <div class="login-box" >
        <div class="logo">
            <a style="color: white; font-family: 'Raleway', sans-serif;">The Book Planet</a>
            <!-- <img src="{{ asset('bsb/images/logo.png')}}" style="height: 50px;" alt="PLS Automobile Services Pvt. Ltd."> -->
                <!-- PLS Automobile Services Pvt. Ltd. -->
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="{{ route('login') }}" >
					{{ csrf_field() }}
                    <div class="msg"><h4>Sign in</h4>
						@if ($errors->has('email'))
							<div class="alert alert-danger">
								<strong>Oh sorry!</strong>
								{{ $errors->first('email') }}
							</div>
						@endif
						@if ($errors->has('password'))
							<div class="alert alert-danger">
								<strong>Oh sorry!</strong>
								{{ $errors->first('password') }}
							</div>
						@endif
					</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Username" required autofocus>
						</div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
						</div>
                    </div>
                    <div class="row ">
                        <!--<div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="remember" id="rememberme" class="filled-in chk-col-pink" {{ old('remember') ? 'checked' : '' }}>
                            <label for="rememberme" >Remember Me</label>
                        </div>-->
                        <div class="col-xs-4 pull-right">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">LOG IN</button>
                        </div>
                        <div class="col-xs-5 ">
                            <a class="btn bg-purple waves-effect" href="{{ route('register') }}">Register Now!</a>
                        </div>
                    </div>
                    <!-- <div class="row m-t-15 m-b--20">
                        <div class="col-xs-5 pull-right">
                            <a class="btn bg-pink waves-effect" href="{{ route('register') }}">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
							<a href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
        <div class="logo">
            <span style="color: white; font-family: 'Raleway', sans-serif;font-size:10px;" >Designed & Developed By</span>
            <a href="http://techstreet.in/" style="color: white; font-family: 'Raleway', sans-serif;font-size:20px;" >Techstreet Solutions</a>
        </div>
    </div>
</div>



</body>
@endsection