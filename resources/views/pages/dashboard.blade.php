@php
use App\Models\User;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Report;
use App\Models\Booking_invoice;

Report::TopFiveSuppliers()

@endphp
@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('booking-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a href="{{route('bookings.create')}}" class="btn btn-sm btn-success">Add Booking</a>
    @endcan

    @can('vehicle-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a href="{{route('vehicles.create')}}" class="btn btn-sm btn-success">Add Vehicle</a>
    @endcan

    @can('driver-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a href="{{route('drivers.create')}}" class="btn btn-sm btn-success">Add Driver</a>
    @endcan

    @can('supplier-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a href="{{route('suppliers.create')}}" class="btn btn-sm btn-success">Add Supplier</a>
    @endcan
</div>
@endsection

@section('content')

<div class="row">
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ Report::getTodayBookingsCount() }}</h3>
  
          <p>Today's Bookings</p>
        </div>
        <div class="icon">
          <i class="fas fa-car"></i>
        </div>
        <a href="{{route('today-bookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->

  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ Report::getTotalBookingsCount() }}</h3>

        <p>Bookings</p>
      </div>
      <div class="icon">
        <i class="fas fa-book-open"></i>
      </div>
    <a href="{{url('bookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ Report::getTotalDriverCount() }}</h3>

        <p>Drivers</p>
      </div>
      <div class="icon">
        <i class="fas fa-male"></i>
      </div>
      <a href="{{url('drivers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->



  <!-- ./col -->

  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ Report::getTotalVehicleCount() }}</h3>

        <p>Vehicles</p>
      </div>
      <div class="icon">
        <i class="fas fa-car"></i>
      </div>
      <a href="{{url('vehicles')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ Report::getAvailableVehicleCount() }}<!-- /* {{ count(Vehicle::where("status",'available')->get())}} */   --></h3>
    
            <p>Vehicles Available</p>
          </div>
          <div class="icon">
            <i class="fas fa-car"></i>
          </div>
          <a href="{{url('vehicles')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->

    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ count(Vehicle::where("status",'maintenance')->get())}}</h3>
  
          <p>Vehicles in Maintenance</p>
        </div>
        <div class="icon">
          <i class="fas fa-wrench"></i>
        </div>
        <a href="{{url('vehicles')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->

  <!-- ./col -->
  @if (auth()->user()->role == 1)
      
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-dark">
      <div class="inner">
        <h3>{{ Report::getTotalSupplierCount() }}</h3>

        <p>Suppliers</p>
      </div>
      <div class="icon">
        <i class="fas fa-address-book"></i>
      </div>
      <a href="{{url('suppliers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  <!-- ./col -->

        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ count(Booking_invoice::where("status",'unpaid')->get())}}</h3>
      
              <p>Unpaid Invoices</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <a href="{{url('booking-invoice')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
  

  {{-- <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3 id="active-supplier">0</h3>

        <p>Active Suppliers</p>
      </div>
      <div class="icon">
        <i class="fas fa-book-open"></i>
      </div>
      <a href="{{url('active-drivers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3 id="active-driver">0</h3>

        <p>Active Drivers</p>
      </div>
      <div class="icon">
        <i class="fas fa-book-open"></i>
      </div>
      <a href="{{url('active-drivers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3 id="active-staff">0</h3>

        <p>Active Staff</p>
      </div>
      <div class="icon">
        <i class="fas fa-book-open"></i>
      </div>
      <a href="{{url('active-drivers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col --> --}}
</div>

<div class="row">
    <div class="col-md-4" style="
    background: #fff;
">
      <h3 class="text-center">Monthly Report of Bookings</h3>
      <canvas id="montlyBookingLineChart" width="400" height="400"></canvas>
      
      <script>
        var ctx = document.getElementById('montlyBookingLineChart').getContext('2d');

        monthly_booking_data = JSON.parse('{{ Report::MonthlyReport_BarChart() }}');
        
        bar_bg_colors = [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',

          'rgba(255, 255, 132, 1)',
          'rgba(50, 100, 235, 1)',
          'rgba(25, 200, 120, 1)',
          'rgba(250, 192, 192, 1)',
          'rgba(153, 180, 250, 1)',
          'rgba(200, 189, 30, 1)',
        ];

        bar_bg_colors[new Date().getMonth()+1] = 'rgba(255, 159, 64, 0.2)';

        
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Bookings of the month',
                    data: monthly_booking_data,
                    pointBackgroundColor: bar_bg_colors,
                    borderColor: "#E54E30",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
        
    </div>

    <div class="col-md-4" style="
    background: #eee;
">


      <h3 class="text-center">Current Week Report of Bookings</h3>
      <canvas id="weeklyReportofBookings" width="400" height="400"></canvas>
      <script>
        var ctx = document.getElementById('weeklyReportofBookings').getContext('2d');

        WeeklyCompleteBookings = JSON.parse('{{ Report::WeeklyCompleteBookings() }}');
        WeeklyPendingBookings = JSON.parse('{{ Report::WeeklyPendingBookings() }}');

        
      
        bar_bg_colors[new Date().getDay()] = 'rgba(0, 0, 0, 1)';

        
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    label: 'Complete Bookings',
                    data: WeeklyCompleteBookings,
                    pointBackgroundColor: bar_bg_colors,
                    borderColor: "#E54E30",
                    borderWidth: 1
                },
                {
                    label: 'Pending Bookings',
                    data: WeeklyPendingBookings,
                    pointBackgroundColor: bar_bg_colors,
                    borderColor: "green",
                    borderWidth: 1
                }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
    </div>

    <div class="col-md-4" style="
    background: #fff;
">
      <h3 class="text-center">Top 5 Drivers</h3>
      <canvas id="top5driverChart" width="400" height="400"></canvas>
      
      <script>
        var ctx = document.getElementById('top5driverChart').getContext('2d');
  
        data = '{!! Report::TopFiveDriver() !!}';
        console.log(data);
        top_driver_data = JSON.parse(data);
       
        bar_bg_colors = [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
        ];
  
  
        
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: top_driver_data.names,
                datasets: [{
                    label: 'Bookings of the month',
                    data: top_driver_data.data,
                    backgroundColor: bar_bg_colors,
                    borderColor: "",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
    </div>
</div>
{{-- 
<div class="row">
  @if (auth()->user()->role == 1)
      
  <div class="col-md-4" >
    <h1 class="text-center">Top 5 Suppliers</h1>
    <canvas id="top5supplierChart" width="400" height="400"></canvas>
    
    <script>
      var ctx = document.getElementById('top5supplierChart').getContext('2d');

      data = '{!! Report::TopFiveSuppliers() !!}';
      top_supplier_data = JSON.parse(data);
     
      bar_bg_colors = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
      ];


      
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: top_supplier_data.names,
              datasets: [{
                  label: 'Bookings of the month',
                  data: top_supplier_data.data,
                  backgroundColor: bar_bg_colors,
                  borderColor: "",
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero: true
                      }
                  }]
              }
          }
      });
      </script>
  </div>

  @endif

  
</div> --}}
@endsection


@section('js')

<script>
    var suppliers = 0;
    var drivers = 0;
    var staff = 0;

   Echo.join(`chat`)
    .here((users) => {
    
      users.map(function(user){
        if(user.role == 3){
          suppliers++;
        }

        if(user.role == 2){
          staff++;
        }

        if(user.role == 4){
          drivers++;
        }
      });


      $("#active-supplier").text(suppliers);
      $("#active-staff").text(staff);
      $("#active-driver").text(drivers);
    })
    .joining((users) => {

       
        /* New Joined Users */

      if(users.role == 3){
        var users = $("#active-supplier").text();
        var users = parseInt(users);
        $("#active-supplier").text(users+1);
      }

      if(users.role == 2){
        var users = $("#active-staff").text();
        var users = parseInt(users);
        $("#active-staff").text(users+1);
      }

      if(users.role == 4){
        var users = $("#active-driver").text();
        var users = parseInt(users);
        $("#active-driver").text(users+1);
      }

        /* New Joined Users  End*/
     
    })
    .leaving((users) => {

        /* leaved Users */
        if(users.role == 3){
        var users = $("#active-supplier").text();
        var users = parseInt(users);
        $("#active-supplier").text(users-1);
      }

      if(users.role == 2){
        var users = $("#active-staff").text();
        var users = parseInt(users);
        $("#active-staff").text(users-1);
      }

      if(users.role == 4){
        var users = $("#active-driver").text();
        var users = parseInt(users);
        $("#active-driver").text(users-1);
      }

        /* Leaved Users End*/

    });
</script>

@endsection

