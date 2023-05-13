<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @include('assets.css')

    <style>
    .nav-tabs .nav-item{
    border:0px;
    margin: 45px 30px 45px 35px;
    color: #e54e30;
    font-style: oblique;
    font-size: larger;
    font-weight: bold;
  }
</style>
</head>
<body>
<!-- Navbar -->


<nav class="main-header navbar navbar-expand navbar-dark navbar-dark" style="margin-left:0">
    
    <img src="{{ config('app.logo') }}" alt="{{ config('app.logo') }}" title="{{ config('app.name') }}" class="img-circle no-light-box"
        style="width: 60px;padding: 10px;background: #fff;">
     
      <!-- Left navbar links -->
      <ul class="navbar-nav ml-5">
        <li class="nav-item d-none d-sm-inline-block ml-2">
        <a href="{{url('me/trips')}}" class="btn btn-default text-center">
            <i class="nav-icon fas fa-car"></i> Trips
          </a>
        </li>
    
        <li class="nav-item d-none d-sm-inline-block ml-2">
        <a href="{{url('me/profile')}}" class="btn btn-default text-center" title="Your Vehicles">
            <i class="nav-icon fas fa-tachometer-alt"></i> Profile
          </a>
        </li>
    
      </ul>
    
    
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
    
  
    
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
            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span>
          </a>
    
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
    
            <a class="dropdown-item" href="{{url('me/profile')}}"><i class="fas fa-user-circle"></i> Profile</a>
    
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
  </nav>
 
    @yield('content')
    
    @include('assets.js')
    @if (Session::has('success'))


  <script>
      $(document).Toasts('create', {
        title: "Action",
        body: "{!! Session::get('success') !!}",
        autohide: true,
        delay: 3000,
        class: "bg-success",
        icon: 'fas fa-check-square fa-lg'
    });
  </script>


  @endif

  @if (Session::has('warning'))


  <script>
      $(document).Toasts('create', {
        title: "Action",
        body: "{!! Session::get('warning') !!}",
        autohide: true,
        delay: 3000,
        class: "bg-warning",
        icon: 'fas fa-exclamation-circle fa-lg'
    });
  </script>


  @endif

  @if (Session::has('error'))


  <script>
    $(document).Toasts('create', {
      title: "Error",
      body: "{!! Session::get('error') !!}",
      autohide: true,
      delay: 3000,
      class: "bg-danger",
      icon: 'fas fa-exclamation-circle fa-lg'
  });
</script>

  @endif
</body>
</html>