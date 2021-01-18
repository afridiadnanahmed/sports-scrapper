@extends('layouts.app')

@section('content')

<!-- .content -->
<section class="content">
    <div class="custom-container">
        <div class="row">
            <aside class="col-sm-3 fix-width">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Live Matches</h2>
                    </div>
                    <a href="#" class="card-body d-block">
                        <h4 class="card-tag">One day match at Berlin, feb 14,2017</h4>
                        <ul class="list-unstyled">
                            <li class="status-title">
                                <span class="country-name">IND</span>
                                <span class="score">300/8</span>
                            </li>
                            <li class="status-title">
                                <span class="country-name">AUS</span>
                                <span class="score">200</span>
                            </li>
                            <li class="results clearfix">
                                <span class="overs bottom-title">45.5</span> overs
                                <span  class="view-btn pull-right">View</span>
                            </li>
                        </ul>
                    </a>
                    <a href="#"  class="card-body">
                        <h4 class="card-tag">Soccer</h4>
                        <ul class="list-unstyled">
                            <li class="status-title">
                                <span class="country-name">CHE LS EA</span>
                                <span class="score">01</span>
                            </li>
                            <li class="status-title">
                                <span class="country-name">AR SE NAL</span>
                                <span class="score">02</span>
                            </li>
                            <li class="results clearfix">
                                <span class="duration bottom-title">02:10</span>
                                <span class="view-btn pull-right">View</span>
                            </li>
                        </ul>
                    </a>
                    <a href="#" class="card-body">
                        <h4 class="card-tag">Basketball</h4>
                        <ul class="list-unstyled">
                            <li class="status-title">
                                <span class="country-name">SP URS</span>
                                <span class="score">01</span>
                            </li>
                            <li class="status-title">
                                <span class="country-name">WARRIORS</span>
                                <span class="score">02</span>
                            </li>
                            <li class="results clearfix">
                                <span class="duration bottom-title">17:10</span>
                                <span  class="view-btn pull-right">View</span>
                            </li>
                        </ul>
                    </a>
                </div>
                <div class="card m-t-20">
                    <div class="card-header">
                        <h2 class="card-title">Upcoming Series</h2>
                    </div>
                    <a href="#" class="card-body high-light">
                        <h4 class="card-tag">Cricket</h4>
                        <h3 class="status-title">India vs Australia</h3>
                    </a>
                    <a href="#" class="card-body high-light">
                        <h4 class="card-tag">Cricket</h4>
                        <h3 class="status-title">Pakistan Super League</h3>
                    </a>
                    <a href="#" class="card-body high-light">
                        <h4 class="card-tag">Soccer</h4>
                        <h3 class="status-title">Laliga</h3>
                    </a>
                    <a href="#" class="card-body high-light">
                        <h4 class="card-tag">Soccer</h4>
                        <h3 class="status-title">Worls Cup</h3>
                    </a>
                    <a href="#" class="card-body high-light">
                        <h4 class="card-tag">Basektball</h4>
                        <h3 class="status-title">NBA</h3>
                    </a>
                </div>
            </aside>
            <div class="col-sm-6 center-content main-news">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="main-card card">
                            <a href="{{ route('newsDetail',['link'=>$newslist[0]['newsLink']]) }}">
                                <img src="{{ $newslist[0]['image'] }}" alt="news image">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <h4 class="card-tag pull-left">{{ $newslist[0]['class'] }}</h4>
                                        <!--<h4 class="card-tag fade-tag pull-right">{{ $newslist[0]['author'] }}</h4>-->
                                    </div>
                                    <h2 class="news-title">
                                        {{ $newslist[0]['title']  }}
                                    </h2>
                                    <p class="discription">
                                        {{ $newslist[0]['desc'] }}
                                    </p>

                                </div>
                            </a>
                        </article>
                    </div>
                    @for($i = 1;$i <= 2; $i++)
                    <div class="col-md-6">
                        <article class="main-card card">
                            <a href="{{ route('newsDetail',['link'=>$newslist[$i]['newsLink']]) }}">
                                <img src="{{ $newslist[$i]['image'] }}" alt="">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <h4 class="card-tag pull-left">{{ $newslist[$i]['class'] }}</h4>
                                        <!--<h4 class="card-tag fade-tag pull-right">{{ $newslist[$i]['author'] }}</h4>-->
                                    </div>
                                    <h2 class="news-title">
                                        {{ $newslist[$i]['title'] }}
                                    </h2>
                                    <p class="discription">
                                        {{ str_limit($newslist[$i]['desc'],80) }}
                                    </p>

                                </div>
                            </a>
                        </article>
                    </div>
                    @endfor

                    <div class="center-ad col-sm-12">
                        <img src="{{asset('images/center-content-ad.jpg')}}" alt="">
                    </div>
                    @for($i = 3;$i <= 4; $i++)
                    <div class="col-md-6">
                        <article class="main-card card">
                            <a href="{{ route('newsDetail',['link'=>$newslist[$i]['newsLink']]) }}">
                                <div class="image-container">
                                    <img src="{{ $newslist[$i]['image'] }}" alt="">
                                </div>
                                <div class="card-body">
                                    <div class="clearfix">
                                        <h4 class="card-tag pull-left">{{ $newslist[$i]['class'] }}</h4>
                                        <!--<h4 class="card-tag fade-tag pull-right">{{ $newslist[$i]['author'] }}</h4>-->
                                    </div>
                                    <h2 class="news-title">
                                        {{ $newslist[$i]['title'] }}
                                    </h2>
                                    <p class="discription">
                                        {{ str_limit($newslist[$i]['desc'],80) }}
                                    </p>

                                </div>
                            </a>
                        </article>
                    </div>
                    @endfor
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center m-t-27">
                        <a href="#" class="btn-primary btn btn-lg">View All News</a>
                    </div>
                </div>
            </div>
            <aside class="col-sm-3 fix-width">
                <div class="card card-md">
                    <div class="card-header">
                        <h2 class="card-title">Connect Now</h2>
                    </div>
                    <div class="card-body">
                        <a href="#" class="btn-inverted d-block">Login</a>
                        <a href="#" class="btn-inverted d-block m-t-10">Sign Up</a>
                    </div>
                </div>
                <div class="card card-md m-t-20">
                    <div class="card-header">
                        <h2 class="card-title">Download App</h2>
                    </div>
                    <div class="card-body">
                        <a href="#" class="d-block icon-btn m-t-10"><img src="{{asset('images/Android_gray.svg')}}" alt="">Android App</a>
                        <a href="#" class="d-block icon-btn m-t-20"><img src="{{asset('images/iOS_gray.svg')}}" alt="">iOS App</a>
                    </div>
                </div>
                <div class="card card-md m-t-20">
                    <div class="card-header">
                        <h2 class="card-title">Follow Us</h2>
                    </div>
                    <div class="card-body">
                        <a href="#" class="d-block icon-btn m-t-10"><img src="{{asset('images/facebook.svg')}}" alt="">Facebook</a>
                        <a href="#" class="d-block icon-btn m-t-20"><img src="{{asset('images/twitter.svg')}}" alt="">Twitter</a>
                    </div>
                </div>
                <div class="aside-ad m-t-20">
                    <a href="#" class="d-block">
                        <img src="{{asset('images/aside-ad.png')}}" alt="">
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection