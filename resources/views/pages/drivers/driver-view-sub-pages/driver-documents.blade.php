<div class="row">
    <div class="col-md-4">
       <!-- About Me Box -->
       <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">License Detail</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong>
  
          <span class="text-muted">
           {{ $user->license_expiry}}
          </span>
          
          <hr>
          <embed src="{{ asset('public/assets/drivers/license_image/'. $user->license_image ) }} " style="width:100%">
            <a target="_blank" href="{{ asset('public/assets/drivers/license_image/'. $user->license_image ) }}">View License Document</a>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
<!-- /*   -->
<!--      <div class="col-md-4"> -->
      <!-- About Me Box -->
<!--       <div class="card card-primary"> -->
<!--        <div class="card-header"> -->
<!--          <h3 class="card-title">RTA Card Detail</h3> -->
<!--        </div> -->
       <!-- /.card-header -->
<!--        <div class="card-body"> -->
<!--          <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong> -->
  
<!--          <span class="text-muted"> -->
<!--           {{ $user->emirates_expiry }} -->
<!--          </span> -->
  
<!--          <hr>
  
         <embed src="{{ asset('public/assets/drivers/rta_card_image/'. $user->rta_card_image ) }} " style="width:100%"> -->
<!--           <a target="_blank" href="{{ asset('public/assets/drivers/rta_card_image/'. $user->rta_card_image ) }}">View RTA Card Document</a> -->
  
<!--        </div> -->
       <!-- /.card-body -->
<!--      </div> -->
     <!-- /.card -->
<!--    </div> -->
  
<!--    <div class="col-md-4"> -->
    <!-- About Me Box -->
<!--     <div class="card card-primary"> -->
<!--      <div class="card-header"> -->
<!--        <h3 class="card-title">Emirates Detail</h3> -->
<!--      </div> -->
     <!-- /.card-header -->
<!--      <div class="card-body"> -->
<!--        <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong> -->
  
<!--        <span class="text-muted"> -->
<!--         {{ $user->rta_card_expiry }} -->
<!--        </span> -->
  
<!--        <hr>
  
       <embed src="{{ asset('public/assets/drivers/emirates_id/'. $user->emirates_id ) }} " style="width:100%"> -->
<!--         <a target="_blank" href="{{ asset('public/assets/drivers/emirates_id/'. $user->emirates_id ) }}">View Emirates ID</a> -->
  
<!--      </div> -->
     <!-- /.card-body -->
<!--    </div> -->
   <!-- /.card -->
<!--   </div> -->
<!-- */  -->

  
    </div>