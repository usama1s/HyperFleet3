  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link text-center">
      <img src="{{ asset("/public/dist/img/hyperlogo.png")}}" alt="AdminLTE Logo" class=" img-circle elevation-3"
           style="opacity: .8;width: 60px;padding: 10px;background: #fff;">
      <span class="brand-text font-weight-light d-block">Hyper Fleet</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset("/public/dist/img/user2-160x160.jpg")}}" class="img-circle elevation-2"  alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            
               
               {{-- Booking Menu Items --}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Bookings
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Bookings</p>
                </a>
              </li>
             
            </ul>
          </li>
           {{-- Vehicles Menu Items --}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-car"></i>
              <p>
                Vehicles
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="{{ route('vehicles.create') }}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New Vehicle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vehicles.index') }}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Manage Vehicle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Under Maintaince</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('vehicles-classes.index') }}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Vehicle Class</p>
                </a>
              </li>
            </ul>
          </li>
          {{-- Driver Menu Items --}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Drivers
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="{{ route('drivers.create') }}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New Driver</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('drivers.index') }}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Manage Driver</p>
                </a>
              </li>           
            </ul>
          </li>
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>