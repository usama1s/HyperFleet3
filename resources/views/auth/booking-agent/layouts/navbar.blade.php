<nav class="main-header navbar navbar-expand-lg navbar-dark">
  <a href="{{ url('/') }}" class="navbar-brand">
    <img src="{{ config('app.logo') }}" alt="{{ config('app.logo') }}" title="{{ config('app.name') }}" class="no-light-box"
        style="width: 60px;padding: 10px;background: transparent;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <!-- Left navbar links -->
    <ul class="navbar-nav ml-5" id="main-menu">
      <li class="nav-item d-sm-inline-block ml-2">
        <a href="{{url('booking-agent')}}" class="btn btn-default text-center">
          <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
        </a>
      </li>
           
      <li class="main_nav_menu nav-item  d-sm-inline-block ml-2">
        <a href="#" class="btn btn-default text-center">
          <i class="nav-icon fas fa-car"></i> Bookings
        </a>
        
        <ul class="nav_sub_menu">
          <li><a href="{{route('my-booking-agent.create-booking')}}" class="sub-item">Add New Booking</a></li>
          <li><a href="{{route('my-booking-agent.booking.index')}}" class="sub-item">Bookings</a></li>
        </ul>
      </li>
    </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto ">

    <li class="nav-item mr-5">

      <div id="clock"></div>
      <div id="clock_date"></div>

    </li>

    {{-- Messages Dropdown menu --}}
    {{-- @include('pages.message.navbar-message') --}}
    <!-- Notifications Dropdown Menu -->
    @include('notifications.notifications')

    @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    @if (Route::has('register'))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
    </li>
    @endif
    @else
    <li class="nav-item dropdown">
      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>

        <img class="img-circle manage-thumbnail no-light-box user-navimg" id="" src="{{auth()->user()->bookingAgent->getProfileImage()}}" alt="" />
        {{-- {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span> --}}
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        
        <div class=" dropdown-header noti-title">
          <h6 class="text-overflow m-0">{{ auth()->user()->first_name }} {{ Auth::user()->last_name }}</h6>
       </div>

       <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="{{route('my-booking-agent.profilePage')}}"><i class="fas fa-user-circle"></i> Profile</a>

        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </li>
    @endguest
    
  </ul>
  </div>
</nav>
<div id="menu_toggle_btn">
  <i class="fa fa-caret-down"></i>
</div>
<!-- /.navbar -->