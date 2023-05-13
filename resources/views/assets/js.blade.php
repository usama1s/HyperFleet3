        <!-- jQuery -->
        <script src="{{ asset ('/public/plugins/jquery/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset ('/public/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset ('/public/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
     
        <!-- jQuery Knob Chart -->
        <script src="{{ asset ('/public/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset ('/public/plugins/moment/moment.min.js')}}"></script>
        {{-- <script src="{{ asset ('/public/plugins/daterangepicker/daterangepicker.js')}}"></script> --}}
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset ('/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}">
        </script>
        <!-- Summernote -->
        <script src="{{ asset ('/public/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ asset ('/public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ('/public/dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset ('/public/dist/js/pages/dashboard.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset ('/public/dist/js/demo.js')}}"></script>

        <!-- SweetAlert2 -->
        <script src="{{ asset ('/public/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

        <!-- Select2 -->
        <script src="{{ asset ('/public/plugins/select2/js/select2.full.min.js')}}"></script>

        <!-- PACE Loader -->
        <script src="{{ asset ('/public/plugins/pace-progress/pace.js')}}"></script>



        <!-- Datatables -->
        <script src="{{ asset ('/public/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-select/js/dataTables.select.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>

        <script src="{{ asset ('/public/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/pdfmake/pdfmake.min.js')}}"></script>
        <script src="{{ asset ('/public/plugins/pdfmake/vfs_fonts.js')}}"></script>

        



        <!-- App -->
        <script src="{{ asset ('/public/js/app.js')}}"></script>

        <!-- Custom.js -->
        <script src="{{ asset ('/public/js/custom.js')}}"></script>

        {{-- algolia map cdn --}}
        <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>

        


        @if (Auth::user())
            
    
        <script>
          NotificationReadAsMark();

          var nav_unread_count = document.getElementById("unread_msg_count_navbar");
          var nav_count = parseInt(nav_unread_count.innerText);
          if(isNaN(nav_count)){
            nav_count = 0;
          }

          Echo.private('notifyChannel')
            .listen('MessageEvent', (event) => {
                
             var  msg = event.message;

              if(msg.receiver_id =='{{ Auth::user()->id}}'){
                nav_count = nav_count +1;
                nav_unread_count.innerText = nav_count;
                nav_unread_count.className  = "badge bg-danger float-right";

                var newMsg = `
                
        <a href="${msg.show_link}" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              
            <img src="${msg.img_path}" alt="profile-img" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  ${msg.sender}
                  
                </h3>
                <p class="text-sm">${msg.message} </p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>1 sec ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
                `;
                $("#nav-bar-message-container").prepend(newMsg);
             
                          
              }
               
            });
         
        /* Loading with pace.js */

         // $(document).ajaxStart(function() { Pace.restart(); });

        
        </script>

        @endif

        @section('js')

        @show