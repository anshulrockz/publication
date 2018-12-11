@extends('layouts.basic')
@section('body')
<!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:200,600" rel="stylesheet" type="text/css">
        <style>
            html, body {
                background-color: #87ceeb;
                background: url({{ asset('welcome-bg.jpg')}});
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 120vh;
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

            .content {
                text-align: center;
            }

            .title {
                font-size: 50px;
				color: white;
                font-family: 'Raleway', sans-serif;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            
            <div class="content">
                <div class="title m-b-md">
                    
                <img src="{{ asset('bsb/images/logo.png')}}" style="height: 120px;" alt="The Book Planet"><br>
                The Book Planet<br>
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
	                        
	                        @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 6)
	                        	<br>
	                        	<br>
                                <a class="btn btn-default btn-lg waves-effect" href="{{ url('claim/create') }}"><b>Accidental Vehicle Claim</b></a>
                                <br>
                                <br>
	                        	<a class="btn btn-default btn-lg waves-effect" href="{{ url('challan/create') }}"><b>Create Challan</b></a>
	                        @endif
						@else
							<a class="btn btn-default btn-lg waves-effect" href="{{ route('login') }}"><b>Login</b></a>
							<a class="btn btn-default btn-lg waves-effect" href="{{ route('register') }}"><b>Register</b></a>
						@endauth
					</div>
				@endif
				<br>
				<br>
				
            </div>
        </div>
    @endsection
