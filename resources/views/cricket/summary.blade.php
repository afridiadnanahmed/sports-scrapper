@extends('layouts.app')

@section('content')

<!-- .content -->
<section class="content">
    <div class="live-match-header m-b-20">
        <div class="custom-container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="live-match-wrap">
                        <p class="match-intro">{{ $summary['summary']['tourHeading'] }}</p>
                        <ul class="list-inline table-list">
                            <li class="country-status">
                                <span class="flag-avatar">
                                    <img src="{{ ($summary['teams']['team1-flag'])?$summary['teams']['team1-flag']:"" }}" alt="">
                                </span>
                                <span class="country-name">{{ ($summary['teams']['team1'])?$summary['teams']['team1']:""}}</span>
                                <span class="score-status"><b>{{ ($summary['teams']['team1-score'])?$summary['teams']['team1-score']:""}}</b></span>
                            </li>
                            <!--<li class="overs-time">45.5 overs</li>-->
                            <li class="country-status right">
                                <span class="flag-avatar">
                                    <img src="{{ ($summary['teams']['team2-flag'])?$summary['teams']['team2-flag']:"" }}" alt="">
                                </span>
                                <span class="country-name">{{ ($summary['teams']['team2'])?$summary['teams']['team2']:""}}</span>
                                <span class="score-status"><b>{{ ($summary['teams']['team2-score'])?$summary['teams']['team2-score']:""}}</b></span>
                            </li>
                        </ul>
                        <p class="toss-results">{{ $summary['summary'][0]['title']}}</p>
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
                                    <a href="#scorecard-filter" aria-controls="tab" role="tab" data-toggle="tab">Scorecard</a>
                                </li>
                                <li role="presentation">
                                    <a href="#commentary-filter" aria-controls="tab" role="tab" data-toggle="tab">Commentary</a>
                                </li>
<!--                                <li role="presentation">
                                    <a href="#playing-filter" aria-controls="tab" role="tab" data-toggle="tab">Playing xi</a>
                                </li>-->
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="live-filter">
                                    <div class="table-responsive primary-table">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Batsmen</th>
                                                    <th colspan="3">Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($summary['summary'][0]['batsmen'] as $batsman)
                                                <tr>
                                                    <td colspan="3">{{ $batsman['name'] }}</td>
                                                    <td colspan="3">{{ $batsman['score'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive primary-table">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Bowling</th>
                                                    <th colspan="3">Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($summary['summary'][0]['bowling'] as $bowler)
                                                <tr>
                                                    <td colspan="3">{{ $bowler['name'] }}</td>
                                                    <td colspan="3">{{ $bowler['score'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive primary-table m-t-17">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="font-weight-normal">
                                                    <th>Recent</th>
                                                    <th class="{!! ($commentary['commentary'][0]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][0]['result'] }}</th>
                                                    <th class="{!! ($commentary['commentary'][1]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][1]['result'] }}</th>
                                                    <th class="{!! ($commentary['commentary'][2]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][2]['result'] }}</th>
                                                    <th class="{!! ($commentary['commentary'][3]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][3]['result'] }}</th>
                                                    <th class="{!! ($commentary['commentary'][4]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][4]['result'] }}</th>
                                                    <th class="{!! ($commentary['commentary'][5]['result'] == 'W')?'color-red':'' !!}">{{ $commentary['commentary'][5]['result'] }}</th>
                                                 </tr>
                                            </thead>
                                        </table>
                                    </div>
<!--                                    <h2 class="table-heading info-bg">Commentary</h2>
                                    <div class="media primary-media">
                                        <div class="pull-left left-col" href="#">
                                            <h3 class="average">19.4</h3>
                                            <div class="circle-badge">
                                                0
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eius mod tempor incididunt utdfds labore et dolore magna aliqua.liquip ex ea commodo consequat. </p>
                                        </div>
                                    </div>
                                    <div class="media primary-media">
                                        <div class="pull-left left-col" href="#">
                                            <h3 class="average">19.4</h3>
                                            <div class="circle-badge primary-border">
                                                0
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eius mod tempor incididunt utdfds labore et dolore magna aliqua.liquip ex ea commodo consequat. </p>
                                        </div>
                                    </div>
                                    <div class="media primary-media">
                                        <div class="pull-left left-col" href="#">
                                            <h3 class="average">19.4</h3>
                                            <div class="circle-badge">
                                                0
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eius mod tempor incididunt utdfds labore et dolore magna aliqua.liquip ex ea commodo consequat. </p>
                                        </div>
                                    </div>-->
                                </div>
                                <div role="tabpanel" class="tab-pane" id="scorecard-filter">
                                    <div class="panel-group" id="accordion">
                                        @foreach($scoreBoard['summary'] as $innInd => $inning)
                                        <div class="panel primary-panel">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle {{ ($innInd != 0)?'collapsed':'' }}" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$innInd}}">{{ $inning['title'] }}<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i></a></h4>
                                            </div>
                                            <div id="collapse{{$innInd}}" class="panel-collapse collapse {{ ($innInd == 0)?'in':'' }}">
                                                <div class="panel-body">
                                                    <div class="table-responsive primary-table">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Batsmen</th>
                                                                    <th></th>
                                                                    <th>R</th>
                                                                    <th>B</th>
                                                                    <th>4s</th>
                                                                    <th>6s</th>
                                                                    <th>SR</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach( $inning['batsmen'] as $batsman)
                                                                <tr>
                                                                    <td>{{ $batsman['name'] }}</td>
                                                                    <td width="164" class="player-name">{{ (isset($batsman['result']))?$batsman['result']:'-' }}</td>
                                                                    <td>{{ (isset($batsman['R']))?$batsman['R']:'-' }}</td>
                                                                    <td>{{ (isset($batsman['B']))?$batsman['B']:'-' }}</td>
                                                                    <td>{{ (isset($batsman['4s']))?$batsman['4s']:'-' }}</td>
                                                                    <td>{{ (isset($batsman['6s']))?$batsman['6s']:'-' }}</td>
                                                                    <td>{{ (isset($batsman['SR']))?$batsman['SR']:'-' }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="table-responsive primary-table m-t-17">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Bowling</th>
                                                                    <th>O</th>
                                                                    <th>M</th>
                                                                    <th>R</th>
                                                                    <th>W</th>
                                                                    <th>Econ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach( $inning['bowling'] as $bowler)
                                                                <tr>
                                                                    <td>{{ $bowler['name'] }}</td>
                                                                    <td>{{ $bowler['O'] }}</td>
                                                                    <td>{{ $bowler['M'] }}</td>
                                                                    <td>{{ $bowler['R'] }}</td>
                                                                    <td>{{ $bowler['W'] }}</td>
                                                                    <td>{{ $bowler['ECON'] }}</td>
                                                                </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="commentary-filter">
                                    @foreach($commentary['commentary'] as $com)
                                    <div class="media primary-media">
                                        <div class="pull-left left-col" href="#">
                                            <h3 class="average">{{ $com['ball'] }}</h3>
                                            <div class="circle-badge">
                                                @if(array_key_exists('result', $com))
                                                  {{ $com['result'] }}
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>{{ $com['description'] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
<!--                                <div role="tabpanel" class="tab-pane row" id="playing-filter">
                                    <div class="col-sm-6">
                                        <ul class="group-list">
                                            <li class="info-bg">India</li>
                                            <li>Player 1</li>
                                            <li>Player 2</li>
                                            <li>Player 3</li>
                                            <li>Player 4</li>
                                            <li>Player 5</li>
                                            <li>Player 6</li>
                                            <li>Player 7</li>
                                            <li>Player 8</li>
                                            <li>Player 9</li>
                                            <li>Player 10</li>
                                            <li>Player 11</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <ul class="group-list">
                                            <li class="info-bg">Australia</li>
                                            <li>Player 1</li>
                                            <li>Player 2</li>
                                            <li>Player 3</li>
                                            <li>Player 4</li>
                                            <li>Player 5</li>
                                            <li>Player 6</li>
                                            <li>Player 7</li>
                                            <li>Player 8</li>
                                            <li>Player 9</li>
                                            <li>Player 10</li>
                                            <li>Player 11</li>
                                        </ul>
                                    </div>

                                </div>-->
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