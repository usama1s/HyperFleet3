<!-- Navbar -->
<nav class="main-header navbar navbar-expand-lg navbar-dark">
    <a href="{{ url('/') }}" class="navbar-brand">

        {{-- @php
  if(Setting::getMeta("logo")){
    $logo = asset('public/images')."/". Setting::getMeta("logo");
  }else {
    $logo = asset('public/dist/img/hyperlogo.png');
  }
@endphp --}}

        <img src="{{ config('app.logo') }}" alt="{{ config('app.logo') }}" title="{{ config('app.name') }}"
            class="no-light-box" style="width: 60px;padding: 10px;background:transparent;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left navbar links -->
        <ul class="navbar-nav ml-5" id="main-menu">

            <li class="nav-item  d-sm-inline-block ml-2">
                <a href="{{ url('dashboard') }}" class="btn btn-default text-center">
                    <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            @hasanyrole('admin|supplier')
                <li class="nav-item  d-sm-inline-block ml-2">
                    <a href="{{ route('market-offers') }}" class="btn btn-default text-center">
                        <i class="nav-icon fas fa-calendar-check"></i> Market Offers
                    </a>
                </li>
            @endhasanyrole
            
            @hasrole('supplier')
<li class="nav-item  d-sm-inline-block ml-2">
                    <a href="{{ route('bookings.index') }}" class="btn btn-default text-center">
                        <i class="nav-icon far fa-calendar-check"></i> Bookings</a>
</li>
            @endhasrole
      @unlessrole('admin')
            @canany(['pricing-view', 'pricing-create'])
            <li class="nav-item  d-sm-inline-block ml-2">
                <a href="{{ url('pricings') }}" class="btn btn-default text-center">
                    <i class="nav-icon fas fa-dollar-sign"></i> Pricing Schemes
                </a>
            </li>
            @endcanany
            @hasrole('supplier')
                <li class="nav-item  d-sm-inline-block ml-2">
                            <a href="{{ route('drivers.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-friends"></i> Drivers
                            </a>
                </li>
            @endhasrole
                @canany(['vehicle-view', 'vehicle-create', 'pricing-view', 'pricing-create'])

                    <li class="main_nav_menu nav-item  d-sm-inline-block ml-2">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-default text-center">
                            <i class="nav-icon fas fa-car"></i> Vehicles
                        </a>

                        <ul class="nav_sub_menu ">
                            @can('vehicle-create')
                                <li>
                                    <a href="{{ route('vehicles.create') }}" class="sub-item">
                                        <i class="nav-icon fas fa-plus-circle "></i> Add New Vehicle
                                    </a>
                                </li>
                            @endcan
                            @canany(['vehicle-view', 'vehicle-edit'])
                            <li>
                                    <a href="{{ route('vehicles.index') }}" class="btn btn-default text-left sub-item">
                                        <i class="nav-icon fas fa-car"></i> Manage Vehicles
                                    </a>
                                </li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany
            @endunlessrole

            @canany(['shift-view', 'shift-create', 'shift-edit'])
                <li class="nav-item  d-sm-inline-block ml-2">
                    <a href="{{ route('shift.index') }}" class="btn btn-default text-center">
                        <i class="nav-icon far fa-clock"></i> Shifts
                    </a>
                </li>
            @endcanany
                        
            @hasrole('admin')
                <li class="nav-item  d-sm-inline-block ml-2">
                    <a href="{{ url('vehicles-classes') }}" class="btn btn-default text-center">
                        <i class="nav-icon fas fa-money-check"></i> Vehicle Classes
                    </a>
                </li>
            @endhasrole

                @unlessrole('supplier')
            <li class="main_nav_menu nav-item  d-sm-inline-block ml-2">
                <a href="#" class="btn btn-default text-center">
                    <i class="nav-icon fas fa-users"></i> Users
                </a>
                <ul class="nav_sub_menu">
      @unlessrole('admin')
                    @canany(['driver-view', 'driver-create', 'driver-edit'])
                        <li>
                            <a href="{{ route('drivers.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-friends"></i> Drivers
                            </a>
                        </li>
                    @endcanany
      @endunlessrole

                    @canany(['supplier-view', 'supplier-create', 'supplier-edit'])
                        <li>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-tie"></i> Suppliers
                            </a>
                        </li>
                    @endcanany

                    @canany(['booking-agent-view', 'booking-agent-create', 'booking-agent-edit'])
                        <li>
                            <a href="{{ route('booking-agent.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-friends"></i> Booking Agents
                            </a>
                        </li>
                    @endcanany

                    @canany(['staff-view', 'staff-create', 'staff-edit'])
                        <li>
                            <a href="{{ route('staff.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-friends"></i> Staff
                            </a>
                        </li>
                    @endcanany

                    @canany(['customer-view', 'customer-create', 'customer-edit'])

                        <li>
                            <a href="{{ url('customers') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-friends"></i> Customers
                            </a>
                        </li>

                    @endcanany

                    @canany(['role-view', 'role-create', 'role-edit'])
                        <li>
                            <a href="{{ route('role.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-users-cog"></i> Roles
                            </a>
                        </li>
                    @endcanany

                    @canany(['permission-view', 'permission-create', 'permission-edit'])
                        <li>
                            <a href="{{ route('permission.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-user-lock"></i> Permissions
                            </a>
                        </li>
                    @endcanany
                  @endunlessrole                  
                 </ul> 
             </li>

            @hasanyrole('admin|booking-agent|staff')
                <li class="main_nav_menu nav-item  d-sm-inline-block ml-2">

                    <a href="{{ route('bookings.index') }}" class="btn btn-default text-center">
                        <i class="nav-icon far fa-calendar-check"></i> Bookings</a>

                    <ul class="nav_sub_menu">
                        @can('booking-create')
                            <li>
                                <a href="{{ route('bookings.create') }}" class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-plus-circle"></i> Add new Booking
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('vouchers.index') }}" class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-percentage"></i> Vouchers
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('today-bookings') }}" class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-calendar-check"></i> Manage Bookings
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('bookings.index') }}" class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-calendar-check"></i> Search Bookings
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endhasanyrole

            @canany(['accounts-view'])
                <li class="main_nav_menu nav-item  d-sm-inline-block ml-2">
                    <a href="#" class="btn btn-default text-center">

                        <i class="nav-icon fas fa-file-invoice"></i> Accounts
                    </a>

                    <ul class="nav_sub_menu">

                        <li>
                            <a href="{{ route('booking-invoice.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-clipboard-list"></i> Invoices
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('payments.index') }}" class="btn btn-default text-left sub-item">
                                <i class="nav-icon fas fa-clipboard-list"></i> Payments
                            </a>
                        </li>

                        @if (auth()->user()->role == 1)
                            <li>
                                <a href="{{ route('account.supplier') }}" class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-clipboard-list"></i> Supplier Payable
                                </a>
                            </li>
                        @elseif(auth()->user()->role == 3)
                            <li>
                                <a href="{{ route('account.supplierbyid', auth()->user()->id) }}"
                                    class="btn btn-default text-left sub-item">
                                    <i class="nav-icon fas fa-clipboard-list"></i> Booking Report
                                </a>
                            </li>
                        @endif

<!--                         <li> -->
<!--                             <a href="{{ route('account.drivers') }}" class="btn btn-default text-left sub-item"> -->
<!--                                 <i class="nav-icon fas fa-clipboard-list"></i> Drivers Payable -->
<!--                             </a> -->
<!--                         </li> -->

                </ul>
            </li>

            @endcanany

            @if (auth()->user()->role == 3)
                
            <li class="nav-item  d-sm-inline-block ml-2">             
               <select class="form-control" name="" id="sale_status">
                <option class="green" value="sale_on" {{ (auth()->user()->supplier->sale_status == 0) ? "selected" : "" }}>Sale On</option>
                <option class="red" value="sale_off" {{ (auth()->user()->supplier->sale_status == 1) ? "selected" : "" }}>Sale Off</option>
               </select>              
            </li>
            
            @endif

        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <li class="nav-item mr-4">

                <div id="clock"></div>
                <div id="clock_date"></div>

            </li>

            <!-- Messages Dropdown Menu -->

            @include('pages.message.navbar-message')

            <!-- Notifications Dropdown Menu -->
            @include('notifications.notifications')

            @guest
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        @if (auth()->user()->role == 3)
                            <img class="img-circle manage-thumbnail no-light-box user-navimg" id="d_img_preview_edit"
                                src="{{ asset('public/storage/assets/suppliers') }}/{{ auth()->user()->supplier->image }}"
                                alt="" />
                        @elseif(auth()->user()->role == 2)
                            <img class="img-circle manage-thumbnail no-light-box user-navimg" id="d_img_preview_edit"
                                src="{{ asset('public/assets/staff') }}/{{ auth()->user()->staff->image }}"
                                alt="" />
                        @else
                            <img src="{{ asset('public/images/default-user.jpg') }}" alt=""
                                class="img-circle manage-thumbnail no-light-box user-navimg">
                        @endif

                        {{-- <img src="{{asset('public/drivers/1.jpg')}}" alt="" class="img-circle manage-thumbnail no-light-box user-navimg"> --}}

                        {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre> --}}
                        {{-- {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span> --}}
                    </a>


                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <div class=" dropdown-header noti-title">

                            <h6 class="text-overflow m-0">{{ auth()->user()->first_name }} {{ Auth::user()->last_name }}
                            </h6>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('profile') }}"><i class="fas fa-user-circle"></i>
                            Profile</a>

                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                            <i class="fas fa-user-cog"></i> {{ __('Settings') }}
                        </a>

                        <a class="dropdown-item" href="{{ route('apikey.index') }}">
                            <i class="fas fa-key"></i> {{ __('Keys') }}
                        </a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
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
</div><!-- /.navbar -->