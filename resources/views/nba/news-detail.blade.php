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
                                       
                                            <img src="{{ $newsDetail['image'] }}" alt="">
                                            <div class="card-body">
                                                <div class="clearfix">
                                                    <h4 class="card-tag pull-left">{{ $newsDetail['date'] }}</h4>
                                                    <h4 class="card-tag fade-tag pull-right">{{ $newsDetail['author'] }}</h4>
                                                </div>
                                                <h2 class="news-title">
                                                    {{ $newsDetail['title'] }}
                                                </h2>
                                                {!! $newsDetail['details'] !!}

                                            </div>
                                       
                                    </article>
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
                                    <a href="#" class="d-block icon-btn m-t-10"><img src="images/Android_gray.svg" alt="">Android App</a>
                                    <a href="#" class="d-block icon-btn m-t-20"><img src="images/iOS_gray.svg" alt="">iOS App</a>
                                </div>
                            </div>
                            <div class="card card-md m-t-20">
                                <div class="card-header">
                                    <h2 class="card-title">Follow Us</h2>
                                </div>
                                <div class="card-body">
                                    <a href="#" class="d-block icon-btn m-t-10"><img src="images/facebook.svg" alt="">Facebook</a>
                                    <a href="#" class="d-block icon-btn m-t-20"><img src="images/twitter.svg" alt="">Twitter</a>
                                </div>
                            </div>
                            <div class="aside-ad m-t-20">
                                <a href="#" class="d-block">
                                    <img src="images/aside-ad.png" alt="">
                                </a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
            <!-- /.content -->

@endsection