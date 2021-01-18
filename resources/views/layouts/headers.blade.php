@php

 $curRoute = trim(Route::getCurrentRoute()->getPrefix(),'/');
 
@endphp

<!-- Header -->
<header class="main-header">
    <nav class="navbar" role="navigation">
        <div class="custom-container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle x collapsed" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('images/Logo-01.svg')}}">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav nav-primary">
                    <li class="{{ ($curRoute == 'cricket')?'active':'' }}"><a href="{{route('cricketHome')}}">Cricket</a></li>
                    <li class="{{ ($curRoute == 'football')?'active':'' }}"><a href="{{route('footBallHome')}}">Soccer</a></li>
                    <li class="{{ ($curRoute == 'nba')?'active':'' }}"><a href="{{route('nbaHome')}}">Basketball</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#" class="icons"><img src="{{asset('images/iOS.svg')}}" width="19" height="20" alt=""></a></li>
                    <li><a href="#" class="icons"><img src="{{asset('images/Android_gray-01.svg')}}" width="19" height="20" alt=""></a></li>
                    <li class="login-wrap"><a class="btn-primary btn"  data-toggle="modal" href='#login-modal'>Login</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>
</header>
<!-- /Header -->