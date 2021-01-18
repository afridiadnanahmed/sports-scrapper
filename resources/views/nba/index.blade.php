@extends('layouts.app')

@section('content')

<!-- .content -->
<div class="calender-filter">
    <div class="custom-container">
        <ul class="list-inline list-bordered">
            <li class="prev calender-controls"><a href="#"><img class="rotate-y" src="images/right-arrow.svg" alt=""></a></li>
            <li><a href="#">16 sep 2017</a></li>
            <li><a href="#">17 sep 2017</a></li>
            <li><a href="#">18 sep 2017</a></li>
            <li class="active"><a href="#">Today</a></li>
            <li><a href="#">20 sep 2017</a></li>
            <li><a href="#">21 sep 2017</a></li>
            <li><a href="#">22 sep 2017</a></li>
            <li class="next calender-controls"><a href="#"><img src="images/right-arrow.svg" alt=""></a></li>
        </ul>
    </div>
</div>
<section class="content">
    <div class="custom-container">
        <div class="row column-2-wrapper">
            <div class="col-sm-9 center-content">
                <div class="wide-card">

                    <div class="row">
                        @if(isset($noMatches))
                        <div class="col-sm-12">
                            <h3>No Matches For Today.</h3>
                        </div>
                        @else
                        @foreach($matches as $mInd => $match)
                        <div class="col-sm-6">
                            <div class="card card-bordered">
                                <!--<h4 class="card-tag">{{ $match['match-info'] }}</h4>-->
                                <ul class="list-unstyled">
                                    <li class="status-title">
                                        <span class="country-name">{{ $match['team1'] }}</span>
                                        <span class="score">{{ $match['team1-score'] }}</span>
                                    </li>
                                    <li class="status-title">
                                        <span class="country-name">{{ $match['team2'] }}</span>
                                        <span class="score">{{ $match['team2-score'] }}</span>
                                    </li>

                                    <li class="toss-results">
                                        <span class="overs bottom-title">{{ $match['match-info'] }}</span>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                                <ul class="list-bordered list-inline m-t-16">
                                    <li><a href="{{ route('summary', ['link' => $match['matchLink']]) }}">Live</a></li>
                                    <li><a href="{{ route('commentary', ['link' => $match['matchLink']]) }}">Commentary</a></li>
                                    <li><a href="{{ route('squad', ['link' => $match['matchLink']]) }}">Playing V</a></li>
                                </ul>
                            </div>
                        </div>
                        {!! ($mInd != 0 && $mInd%2 != 0)?'<div class="clearfix"></div>':'' !!}
                        @endforeach
                        @endif

                    </div>

                </div>
            </div>
            <aside class="col-sm-3">
                <div class="card card-md">
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