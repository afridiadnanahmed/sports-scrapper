@include('layouts.modal')

@php
$token = \App\Http\helpers::getToken()
@endphp

<script type="text/javascript">
    token = '{{$token}}';
</script>

<div id="header" class="header" ng-app="navbar" ng-controller="navbarCtrl" ng-cloak>
    <div id="logo">
        <a href="{{route('dashboard')}}">
            <img class=logo src="{{asset('images/logo.png')}}">
        </a>

    </div>

    <div class="header-btn">
        

        
        <a  href="{{route('stats')}}" type="button" class="push-btn btn btn-info">Stats</a>
        <div class="head-drop-btn btn-group">


            <button type="button" class="push-btn btn btn-danger">All Team</button>

            <button type="button" class="btn btn-dropdown btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a >Action 1</a></li>
                <li><a >Action 2</a></li>
            </ul>
        </div>
       

        <div class="head-drop-btn btn-group">
            <a type="button" class="push-btn btn btn-warning" href="{{route('newOrders')}}">Add New Job</a>
            <button type="button" class="btn btn-dropdown btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a >Action 1</a></li>
                <li><a >Action 2</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <a href="{{route('otpPage')}}" type="button">
                <img class="otp-logo" src="{{asset('images/otp-logo.png')}}">
                <span id="otp-badge" class="badge" style="display: none;">0</span>
            </a>
        </div>
        <div class="btn-group">
            <a id="service-btn" href="#" type="button" onclick="toggleDropdownNavbar(this, event)">
                <span class="glyphicon glyphicon-th"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">

                <li><a href="{{route('makes')}}">Makes</a>
                <li><a href="{{route('models')}}">Models</a></li>
                <li><a href="{{route('modelnyears')}}">Model and Years</a></li>

                <li class="divider"></li>
                <li><a href="{{route('services')}}">Services</a></li>
                <li><a href="{{route('brands')}}">Service's Brands</a></li>
                <li><a href="{{route('oils')}}">Oil Brands</a></li>
                <li><a href="{{route('airFilters')}}">Air Filters</a></li>
                <li><a href="{{route('oilFilters')}}">Oil Filters</a></li>
                <li><a href="{{route('batteries')}}">Batteries</a></li>
                <li><a href="{{route('breakPads')}}">Brake Pad</a></li>

                <li class="divider"></li>
                <li><a href="{{route('orders')}}">Orders</a></li>
                <li><a href="{{route('resources')}}">Resource Management</a></li>
                <li><a href="{{route('users')}}">User Management</a></li>
                <li class="divider"></li>
                <li><a href="{{route('groups')}}">Groups</a></li>
                <li><a href="{{route('promos')}}">Promo Codes</a></li>
                <li><a href="{{route('campaign')}}">Campaign Management</a></li>
                <li><a href="{{route('ask')}}">Ask A Mechanic</a></li>


            </ul>
        </div>
        <div class="btn-group">
            <a href="#" {{--onclick="$('#notification-bell').hide();"--}} type="button" onclick="toggleDropdownNavbar(this, event)">
                <span class="glyphicon glyphicon-bell" aria-hidden="true">
                    <span id="notification-bell" class="badge" style="display: none;">0</span>
                </span>
            </a>
            <ul id="notifDropdown" class="dropdown-menu dropdown-menu-right notif-dropdown" role="menu">
                <p class="title">Notfications</p>
                <div class="item-container">
                    <li class="item" ng-repeat="item in notifications">
                        <div class="item-action">
                            <a href="#" ng-show="item.read_at === null" ng-click="markRead($index)"><p class="mark-read link-p">Mark as read</p></a>
                            <a ng-hide="item.read_at === null"><p class="mark-read link-d">Read!</p></a>
                            <div class="btn-group reminder-date">
                                <button type="button" class="btn btn-default remind" onclick="toggleDropdownNavbar(this, event)">Remind Later</button>
                                <ul class="dropdown-menu dropdown-menu-right" id="dateDropdown" role="menu" aria-labelledby="dLabel">
                                    <datetimepicker data-ng-model="data.date" data-on-set-time="customReminder(newDate, oldDate, $index)" data-before-render="disableDateBeforeRender($view, $dates, $leftDate, $upDate, $rightDate)" data-datetimepicker-config="{ minView: 'day' }"></datetimepicker>
                                </ul>
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-reminder" onclick="toggleDropdownNavbar(this, event)">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" id="reminderDropdown">
                                    <li><a href="#" ng-click="remindLater($index, false, 1)">Tomorrow</a></li>
                                    <li><a href="#" ng-click="remindLater($index, false, 7)">Next Week</a></li>
                                </ul>
                            </div>
                        </div>
                        <%item.data.reason%> <i ng-hide="item.data.type === 6">(<span class="link-p">#<%item.data.order_id%></span>)</i><i ng-show="item.data.type === 6">(<span class="link-p"><%item.data.phone_number%></span>)</i><br>
                        <a href="{{route('viewOrders')}}?id=<%item.data.order_id%>" target="_blank" class="link-p" ng-hide="item.data.type === 6">View order</a>
                        <a href="{{route('viewUser')}}?id=<%item.data.user_id%>" target="_blank" class="link-p" ng-show="item.data.type === 6">View profile</a>
                    </li>
                    <li class="item no-new" ng-hide="notifications.length">No notification today!</li>
                </div>
                <a href="{{route('notifications')}}"><p class="button">View all notifications ></p></a>
            </ul>
        </div>
        <div class="btn-group">
            <a id="user-btn" href="#" type="button" onclick="toggleDropdownNavbar(this, event)">
                <img class="avatar" src="{{asset('images/avatar.png')}}">
            </a>
            <ul class="dropdown-menu dropdown-menu-right user-dropdown" role="menu">
                <li> <h5 class="text-danger">{{\Sentinel::getUser()->first_name}}</h5></li>
                <li>
                    <form class="form" role="form" method="post" action="{{route('signout')}}" accept-charset="UTF-8" id="login-nav">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                        <div class="form-group">
                            <button type="submit" class="btn btn-signout btn-success btn-block">Sign Out</button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))

    <p class="alert alert-{{ $msg}}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    @endif
    @endforeach
</div>

<div class="loader-bg">
    <img class="loader-body" src="{{asset('images/loader.gif')}}">
</div>
