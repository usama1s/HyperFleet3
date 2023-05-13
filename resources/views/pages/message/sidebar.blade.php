
  @php
  use App\Models\User;
  use App\Models\Driver;;
  use Illuminate\Support\Str;
  use App\Models\Staff;

  $users= [];

   if(Auth()->user()->role == 1){
       //admin login
       //$users =  DB::table('users')->where("id",'!=',Auth::user()->id)->get();
       $users =  DB::table('users')->orderBy('id','desc')->get();
       
       
       
  
   }else if(Auth()->user()->role == 3){
       //supplier login
             $supplier = Auth()->user();

             //$admin = DB::table('users')->where("id",'!=',Auth::user()->id)->get();

             $drivers = DB::table('users')
             ->join('drivers', 'users.id', '=', 'drivers.user_id')
             ->where("drivers.supplier_id",$supplier->id)
             ->select("users.*")->get();
  
            $staff = DB::table('users')
             ->join('staff', 'users.id', '=', 'staff.user_id')
             ->where("staff.supplier_id",$supplier->id)
             ->select("users.*")->get();
  
          $users = [];
  
          foreach ($drivers as $value) {
          
            array_push($users,$value);
          }
  
          foreach ($staff as $value) {
          
          array_push($users,$value);
        }
  
       
  
          
  
         
         
   }
   else if(Auth()->user()->role == 2){
       //staff login
  
             $login = Auth()->user();
             $login_staff = Staff::where("user_id",$login->id)->first();
  
             $drivers = DB::table('users')
             ->join('drivers', 'users.id', '=', 'drivers.user_id')
             ->where("drivers.supplier_id",$login_staff->supplier_id)
             ->select("users.*")->get();
  
            $staff = DB::table('users')
             ->join('staff', 'users.id', '=', 'staff.user_id')
             ->where("staff.supplier_id",$login_staff->supplier_id)
             ->where('staff.user_id','!=',$login->id)
             ->select("users.*")->get();
  
            $users = [];
  
            foreach ($drivers as $value) {
  
                array_push($users,$value);
  
            }
  
            foreach ($staff as $value) {
  
                array_push($users,$value);
  
            }
  
           
   }

   else if(Auth()->user()->role == 4){
       //Drver login
  
             $login = Auth()->user();
             $login_driver = Driver::where("user_id",$login->id)->first();
  
             $drivers = DB::table('users')
             ->join('drivers', 'users.id', '=', 'drivers.user_id')
             ->where("drivers.supplier_id",$login_driver->supplier_id)
             ->select("users.*")->get();
  
            $staff = DB::table('users')
             ->join('staff', 'users.id', '=', 'staff.user_id')
             ->where("staff.supplier_id",$login_driver->supplier_id)
             ->where('staff.user_id','!=',$login->id)
             ->select("users.*")->get();
  
            $users = [];
  
            foreach ($drivers as $value) {
  
                array_push($users,$value);
  
            }
  
            foreach ($staff as $value) {
  
                array_push($users,$value);
  
            }

          
           
   }

    $users = json_encode($users);
    
  @endphp
        
    
<button type="button" class="btn btn-dark btn-block mb-3" id="newcomposebtn" data-toggle="modal" data-target="#composeModal">
  Compose
</button>

{{-- compose message modal --}}


<!-- Modal -->

<div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="composeModalLabel" aria-hidden="true">

<div class="modal-dialog modal-lg" role="document">

  <form action="{{route('message.store')}}" method="POST">

<div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title " id="composeModalLabel">New Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <div class="modal-body">
            @csrf
              
          <div class="col-md-12">
            <label for="">Contact Found: </label>
            <label class="sr-only" for="inlineFormInputGroup">to</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">@</div>
              </div>
              <select name="to" id="to_mail" multiple="multiple" class="form-control to_mail" style="width:auto; flex:1px;">

                       
                        
              </select>

              
             <script>
               window.messageUser = `{!! $users !!}`;
             </script>
            </div>
          </div>
          <div class="col-md-12">
            <label class="sr-only" for="inlineFormInputGroup">subject</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">Subject</div>
              </div>
              <input type="text" name="subject" id="subject" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <textarea id="msg" name="msg" ></textarea>
            </div>

          
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value='Send Message'>
      </div>

    </div>
  </form>

  </div>

</div>


  <div class="card">
    <div class="card-header">
      <h3 class="card-title">General</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="card-body p-0" style="display: block;">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item active">
          <a href="{{route('message.index')}}" class="nav-link">
            <i class="fas fa-inbox"></i> Inbox


            <span class="{{ App\Models\Message::CountUnRead() ? 'badge bg-danger float-right' : ''}}" id="unread_msg_count">{{App\Models\Message::CountUnRead() }}</span>


          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('message.index',"type=sent")}}" class="nav-link">
            <i class="far fa-envelope"></i> Sent
      
          </a>
        </li>
      </ul>
    </div>
    <!-- /.card-body -->
  </div>



