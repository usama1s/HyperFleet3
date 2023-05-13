<!DOCTYPE html>
<html>

<head>
  @include('assets.meta')
  @include('assets.css')



</head>

<body class="sidebar-collapse">
  <div class="wrapper">

    @include('auth.booking-agent.layouts.navbar')
    {{-- @include('auth.booking-agent.layouts.sidebar') --}}




    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-4">
              <h1 class="m-0 text-dark"> @yield('title')</h1>
            </div><!-- /.col -->
            <div class="col-sm-8">
              @section('breadcrumb')
              @show

            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          @yield('content')
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy; {{ Date('Y') }} <a href="#">Hyper FleetManager</a>.</strong>
      All rights reserved.
      {{-- <div class="float-right d-none d-sm-inline-block">
        <b>Shift Ends In</b> <span id="driver_shift_ends">6:20:2</span>
      </div> --}}
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  @include('assets.js')

  @if (session('error'))
  <script>
    $(document).Toasts('create', {
      title: "Error",
      body: " {{ session('error') }}",
      autohide: true,
      delay: 3000,
      class: "bg-danger",
      icon: 'fas fa-exclamation-circle fa-lg'
    });
  </script>
  @endif

  @if (session('success'))
  <script>
    $(document).Toasts('create', {
      title: "Success",
      body: " {{ session('success') }}",
      autohide: true,
      delay: 3000,
      class: "bg-success",
      icon: 'fas fa-check-circle fa-lg'
    });
  </script>
  @endif

  @if (session('warning'))
  <script>
    $(document).Toasts('create', {
      title: "Warning",
      body: " {{ session('warning') }}",
      autohide: true,
      delay: 3000,
      class: "bg-warning",
      icon: 'fas fa-check-circle fa-lg'
    });
  </script>
  @endif


</body>

</html>