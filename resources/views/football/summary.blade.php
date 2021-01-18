@extends('layouts.app')

@section('content')

<!-- .content -->
<section class="content">
    <div class="live-match-header m-b-20">
        <div class="custom-container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="live-match-wrap">
                        <p class="match-intro">{{ $summary['tourHeading'] }}</p>
                        <ul class="list-inline table-list">
                            <li class="country-status">
                                <span class="flag-avatar">
                                    <img src="{{ (isset($summary['teamInfo']['team1-flag']))?$summary['teamInfo']['team1-flag']:"" }}" alt="">
                                </span>
                                <span class="country-name">{{ ($summary['teamInfo']['team1']['name'])?$summary['teamInfo']['team1']['name']:""}}</span>
                                <span class="score-status"><b>{{ ($summary['teamInfo']['team1']['goals']) }}</b></span>
                            </li>
                            <!--<li class="overs-time">45.5 overs</li>-->
                            <li class="country-status right">
                                <span class="flag-avatar">
                                    <img src="{{ (isset($summary['teamInfo']['team2-flag']))?$summary['teamInfo']['team2-flag']:"" }}" alt="">
                                </span>
                                <span class="country-name">{{ ($summary['teamInfo']['team2']['name'])?$summary['teamInfo']['team2']['name']:""}}</span>
                                <span class="score-status"><b>{{ ($summary['teamInfo']['team2']['goals']) }}</b></span>
                            </li>
                        </ul>
                        <p class="toss-results">{{-- $summary['summary'][0]['title'] --}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-container">
        <div class="row">
            <aside class="col-md-3 fix-width m-b-20">
                <div class="card">
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
            <div class="col-md-6 center-content main-news">
                <div class="row">
                    <div class="col-sm-12">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="list-inline list-bordered m-b-17" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#live-filter" aria-controls="live-filter" role="tab" data-toggle="tab">Live</a>
                                </li>
                                <li role="presentation">
                                    <a href="#commentary-filter" aria-controls="tab" role="tab" data-toggle="tab">Commentary</a>
                                </li>
                                <li role="presentation">
                                    <a href="#squad-filter" aria-controls="tab" role="tab" data-toggle="tab">Playing xi</a>
                                </li>

                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--SUMMARY-->
                                <div role="tabpanel" class="tab-pane active" id="live-filter">
                                    <div class="panel info-panel">
                                        <div class="panel-heading">
                                            <h2 class="panel-title">
                                                Goals Sumary 
                                            </h2>
                                        </div>
                                        <div class="panel-body">
                                            @foreach($summary['summary'] as $sum)
                                            <div class="media summary-media">
                                                <div class="pull-left left-col col-sm-3">
                                                    <span class="min">{{ $sum['time'] }}</span>
                                                    <span class="fade-text">min</span>

                                                </div>
                                                <div class="media-body col-sm-8">
                                                    <div class="media-title player-name">{{ $sum['player'] }}</div>
                                                    <span class="fade-text team-name">{{ $sum['team'] }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>


                                </div>
                                <!--COMMENTARY-->
                                <div role="tabpanel" class="tab-pane" id="commentary-filter">
                                    @foreach($commentary['commentary'] as $com)
                                    <div class="media primary-media">
                                        <div class="pull-left left-col" href="#">
                                            <h3 class="average"></h3>
                                            <div class="circle-badge">
                                                @if(array_key_exists('time', $com))
                                                {{ $com['time'] }}
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>{{ $com['description'] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <!--SQUAD-->
                                <div role="tabpanel" class="tab-pane row" id="squad-filter">
                                    @for($si = 1;$si <= 2; $si++)   
                                    <div class="col-sm-6">
                                        <ul class="group-list">
                                            <li class="info-bg">{{ $squad['squad']["team$si"] }}</li>
                                            @foreach($squad['squad']["team-$si-players"] as $player)
                                              <li>{{ $player['name'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endfor


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="col-md-3 fix-width">
                <div class="card card-md">
                    <div class="card-header">
                        <h2 class="card-title">Download App</h2>
                    </div>
                    <div class="card-body">
                        <a href="#" class="d-block icon-btn m-t-10"><img src="{{ asset('images/Android_gray.svg') }}" alt="">Android App</a>
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