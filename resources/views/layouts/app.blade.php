<!DOCTYPE html>
<html>

<head>
    @include('assets.meta')
    @include('assets.css')
</head>

<body class="sidebar-collapse">

    <div class="wrapper" id="app">

        @include('layouts.navbar')
        {{-- @include('layouts.sidebar') --}}

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-3">
                            <h1 class="m-0 text-dark"> @yield('title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-3">

                        </div>
                        <div class="col-sm-6">
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
            <strong>Copyright &copy; {{ Date('Y') }} <a href="https://hypersoftwares.com" target="_blank">Hyper Software Solutions LTD</a>.</strong>
            All rights reserved.
            {{-- <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.2
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

    <script>
        $(document).ready(function() {

            $("#sale_status").change(function() {
                var t_url = "{{ route('supplier.sale-status') }}";
                $.ajax({
                    url: t_url,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        sale_status: $(this).val()
                    },
                    success: function(result) {
                        if (result.status) {
                            $(document).Toasts('create', {
                                title: "Success",
                                body: result.message,
                                autohide: true,
                                delay: 3000,
                                class: "bg-success",
                                icon: 'fas fa-check-square fa-lg'
                            });
                        }else{

                          $(document).Toasts('create', {
                                title: "Error",
                                body: result.message,
                                autohide: true,
                                delay: 3000,
                                class: "bg-danger",
                                icon: 'fas fa-exclamation-circle fa-lg'
                            });

                        }
                    }
                });
            });
        });
    </script>


</body>

</html>
