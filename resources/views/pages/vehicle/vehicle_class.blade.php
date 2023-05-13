@php
  
  $vehicle_class_prev_form_type = session('vehicle_class_prev_form_type');
  session()->forget('vehicle_class_prev_form_type');

@endphp

@extends('layouts.app')

@section('title', 'Vehicle Classes')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
  <li class="breadcrumb-item"><a href="{{url('/vehicles')}}">Vehicles </a></li>
  <li class="breadcrumb-item active">Classes</li>
</ol>

@endsection

@section('content')

<!-- Add New Button for Vehicle Class -->

<div class="float-right">
  @role('admin')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
  @endrole

  @role('admin')
    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#newVehicleClassModal"> Add New </button>
  @endrole
</div>

@if (auth()->user()->role == 1)
<!-- Modal for Vehicle Class -->
<div class="modal fade" id="newVehicleClassModal" tabindex="-1" role="dialog"
  aria-labelledby="newVehicleClassModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form method="post" action="{{ route('vehicles-classes.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="newVehicleClassModalLabel">Add New Vehicle Class</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">

              <div class="col">
                <label for="add-new-input">Vehicle Class Name:</label>
                <input class="form-control" type="text" name="name" id="vehicle_class_name"
                  placeholder="First Class, Business Class" required>

                  @error('name')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror

              </div>
              
<!--               <div class="col"> -->
<!--                 <label for="add-new-input">Price:</label> -->
<!--                 <div class="input-group"> -->
<!--                   <input class="form-control" type="number" name="vehicle_class_price" id="vehicle_class_price" placeholder="Price" required autocomplete="off" aria-label="Amount (to the nearest dollar)"> -->
<!--                   <div class="input-group-append"> -->
<!--                   <span class="input-group-text" id="discount-symbol">{{ Config('currency-symbol')}}</span> -->
<!--                   </div> -->
<!--                 </div> -->

<!--                 @error('vehicle_class_price') -->
<!--                 <div class="invalid-feedback" style="display:block;" role="alert"> -->
<!--                   <strong>{{ $message }}</strong> -->
<!--                 </div> -->
<!--                 @enderror -->
<!--               </div> -->
              
          </div><br/>
          <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="tooltip2"><label>Accepted Vehicles:</label>
  <span class="tooltip2text">
  <table class="odnunset">
<caption><!--   Accepted Vehicles: --></caption>
  <thead>
    <tr>
      <th scope="col">Add all Car Models in this Vehicle Class here.</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Carmodel"></td>
    </tr>
  </tbody>
</table>  
  </span>
</div>
              <textarea  rows="6" cols="60" class="form-control" name="vehicle_class_price_descp" id="vehicle_class_descp" placeholder=""  autocomplete="off"></textarea>
              @error('vehicle_class_price_descp')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
              </div>
              @enderror
            </div>

          </div>

<!-- 
          <div class="row">
            <div class="col">
              <label>Passengers:</label>
              <select class="form-control" name="vehicle_class_price_passangers" required>
                <option value="">Passengers</option>
                @for ($i = 1; $i <= 10; $i++)    
                <option value="{{$i}}">{{$i}}</option>
                @endfor
              <select>
                @error('vehicle_class_price_passangers')
                <div class="invalid-feedback" style="display:block;" role="alert">
                  <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>
            <div class="col">
              <label>Bags:</label>
              <select class="form-control" name="vehicle_class_price_bags" required>
                <option value="">Bags</option>
                @for ($i = 1; $i <= 10; $i++)    
                <option value="{{$i}}">{{$i}}</option>
                @endfor
              <select>
                @error('vehicle_class_price_bags')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
              </div>
              @enderror
            </div>
          </div>
 -->        
</div>

          <div class="col-md-6">
            <div class="form-group">
              <label>Vehicle Class Image:</label>
              <br>
              <div class="imgUploader" style="width:200px; height:200px">
                <input class="form control imgUploaderinput" type='file' name="vehicle_class_thumbnail" id="v_img" required/>
              <img class="imgUploaderImg" id="v_img_preview" src="{{asset('public/images/default-thumbnail.jpg')}}" alt="" />
              </div>
              @error('vehicle_class_thumbnail')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
              </div>
              @enderror
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-info" value="Save">
        </div>
      </form>
    </div>
  </div>
</div>

@endif
@if (auth()->user()->role == 1)
{{-- edit model --}}
<div class="modal fade" id="newVehicleClassEditModal" tabindex="-1" role="dialog"
  aria-labelledby="newVehicleClassEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form method="post" id="vclass_edit" action="" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="newVehicleClassEditModalLabel">Edit Vehicle Class</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col">
              <label for="add-new-input">Vehicle Class Name:</label>
              <input class="form-control" type="text" name="name"  value="{{ old('name') }}" id="vclass_name_update"
                placeholder="First Class, Business Class" required>
                @error('name')
                <div class="invalid-feedback" style="display:block;" role="alert">
                    {{ $message }}
                </div>
                @enderror
            </div>
            
<!--             <div class="col"> -->
<!--               <label for="add-new-input">Price:</label> -->
<!--               <div class="input-group"> -->
<!--                 <input class="form-control" type="number" name="vehicle_class_price" id="vehicle_class_price_update" value="{{ old('vehicle_class_price') }}" placeholder="Price" required autocomplete="off" aria-label="Amount (to the nearest dollar)"> -->
<!--                 <div class="input-group-append"> -->
<!--                 <span class="input-group-text" id="discount-symbol">{{ Config('currency-symbol')}}</span> -->
<!--                 </div> -->
<!--               </div> -->
<!--               @error('vehicle_class_price') -->
<!--             <div class="invalid-feedback" style="display:block;" role="alert"> -->
<!--                   {{ $message }} -->
<!--               </div> -->
<!--               @enderror -->
<!--             </div> -->
            
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label>Description:</label>
              <textarea class="form-control" name="vehicle_class_price_descp" id="vehicle_class_descp_update" placeholder="Description" required autocomplete="off" required></textarea>              
              @error('vehicle_class_price_descp')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
              </div>
              @enderror
            </div>
          </div>

<!--           <div class="row"> -->
<!--             <div class="col"> -->
<!--               <label>Passengers:</label> -->
<!--               <select class="form-control" name="vehicle_class_price_passangers" id="vehicle_class_price_passangers_update" required> -->
<!--                 <option value="">Passengers</option> -->
<!--                 @for ($i = 1; $i <= 10; $i++)     -->
<!--                 <option value="{{$i}}">{{$i}}</option> -->
<!--                 @endfor -->
<!--               <select> -->
<!--                 @error('vehicle_class_price_passangers') -->
<!--                <div class="invalid-feedback" style="display:block;" role="alert"> -->
<!--                   <strong>{{ $message }}</strong> -->
<!--                 </div> -->
<!--                 @enderror -->
<!--             </div> -->
<!--             <div class="col"> -->
<!--               <label>Bags:</label> -->
<!--               <select class="form-control" name="vehicle_class_price_bags" id="vehicle_class_price_bags_update" required> -->
<!--                 <option value="">Bags</option> -->
<!--                 @for ($i = 1; $i <= 10; $i++)     -->
<!--                 <option value="{{$i}}">{{$i}}</option> -->
<!--                 @endfor -->
<!--               <select> -->
<!--                 @error('vehicle_class_price_bags') -->
<!--              <div class="invalid-feedback" style="display:block;" role="alert"> -->
<!--                 <strong>{{ $message }}</strong> -->
<!--               </div> -->
<!--               @enderror -->
<!--             </div> -->
<!--           </div> -->

          <div class="col-md-6">
            <div class="form-group">
              <label>Thumbnail:</label>
              <br>
              <div class="imgUploader" style="width:200px; height:200px">
                <input class="form control imgUploaderinput" type='file' name="vehicle_class_thumbnail" id="v_update_img" />
              <img class="imgUploaderImg" id="v_img_preview_update" src="{{asset('public/images/default-thumbnail.jpg')}}" alt="" />
              </div>
              @error('vehicle_class_thumbnail')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
              </div>
              @enderror
            </div>

          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-info" value="Save">
        </div>
      </form>
    </div>
  </div>
</div>

@endif
{{-- View Vechicle Classes DATA in Table --}}
<form action="{{ route("vehicles-classes.bulkdestroy") }}" method="POST" id="record-form">
  @csrf
<table id="manage_vehicles_classes" style="width:100% background:#fff;" class="table">
  <thead>
    <tr>
      <th style="width:10px;" id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
      <th>#</th>
      <th>Thumbnail</th>
      <th>Name</th>
<!--       {{-- <th>price</th> --}} -->
      <th>Description</th>
<!--       <th>Passengers</th> -->
<!--       <th>Bags</th> -->

    </tr>
  </thead>
  <tbody>
    
    @php
    $sno =1;
    @endphp

    @foreach ($vehicle_classes as $vclass)
    <tr>
        <td style="width:20px;"> <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $vclass->id }}"></td>
        <td>
          {{ $sno++}}

      </td>

      <td>
        @php
             $img_url = asset('public/assets/vehicle-class')."/".$vclass->thumbnail;
             $headers=get_headers($img_url);  

            if(stripos($headers[0],"404 Not Found")){
                $img_url =  asset('public/images/img-placeholder.jpg');
            }
        @endphp
        <img class="manage-thumbnail" src="{{ $img_url }}" width="80">
      </td>

      <td>{{$vclass->name}}

        @if (auth()->user()->role == 1)
            
        
        <span class="action_wapper2">
          {{-- @can('shift-edit') --}}
        <a class="text-success mr-2" href="{{url('vehicles-classes')}}/{{$vclass->id}}/edit" data-vclass="{{json_encode($vclass)}}" data-vclass-img="{{ asset('public/assets/vehicle-class') }}/{{$vclass->thumbnail}}"
            title="edit" data-placement="top" data-toggle="modal" data-target="#newVehicleClassEditModal"><i
              class="fa fa-edit"></i></a> | 
          {{-- @endcan --}}

          {{-- @can('shift-delete') --}}
          <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{url('vehicles-classes')}}/delete/{{$vclass->id}}" title="delete" data-toggle="tooltip" data-placement="top"><i
              class="fa fa-trash"></i></a>
          {{-- @endcan --}}
      </span>
      @endif
      </td>
<!--       {{-- <td>{{ Config('currency-symbol')}} {{$vclass->price}}</td>  --}} -->
      <td>{{$vclass->desc}}</td> 
<!--       <td>{{$vclass->passengers}}</td>  -->
<!--       <td>{{$vclass->bags}}</td>  -->
   
    </tr>
    @endforeach
  </tbody>
</table>
</form>

@endsection

@section('js')

<script>
  // var sno = 1;
   $(document).ready(function() {

     if('{{ $vehicle_class_prev_form_type }}' == "add-new-form"){
      $('#newVehicleClassModal').modal('show')
      
     }

     if('{{ $vehicle_class_prev_form_type }}' == "update-form"){
      $('#newVehicleClassEditModal').modal('show')
     }
    var table = $('#manage_vehicles_classes').DataTable({});
    $('[data-toggle="tooltip"]').tooltip(); 

    if('{{ count($vehicle_classes) }}' < 1){
            $("#thcheck").hide();
           
        }
        
    $('#newVehicleClassEditModal').on('show.bs.modal', function (event) {

      var button = $(event.relatedTarget) // Button that triggered the modal

      var vclass = button.data('vclass');
     // console.log(vclass);
   
      var vclass_img = button.data('vclass-img')

      var modal= $(this)

      // modal.find('.modal-body #vclass_id_update').val(vclass_id)
      modal.find('.modal-body #vclass_name_update').val(vclass.name)
//       modal.find('.modal-body #vehicle_class_price_update').val(vclass.price)
      modal.find('.modal-body #vehicle_class_descp_update').val(vclass.desc)
//       modal.find('.modal-body #vehicle_class_price_passangers_update').val(vclass.passengers)
//       modal.find('.modal-body #vehicle_class_price_bags_update').val(vclass.bags)
      modal.find('.modal-body #v_img_preview_update').attr("src", vclass_img)
    


      $('#vclass_edit').attr("action", "{{route('vehicles-classes.index')}}" + "/" + vclass.id);

})

$("#v_img").change(function() {
      readURL(this, "#v_img_preview");
});

$("#v_update_img").change(function() {
      readURL(this, "#v_img_preview_update");
});



  //   $('#manage_vehicles_classes').DataTable({
  //     ajax: '{{ url("/api/vehicles_classes") }}',
  //     columns: [{
  //         "mRender": function() {
  //           var checkbox = '<input type="checkbox">';
  //           return checkbox;

  //         }
  //       },
  //       {
  //         "mRender": function(a, b, row) {
  //           var id = row['id'];
  //           var view = "{{ url('vehicles')}}/" + id;
  //           var edit = "{{url('vehicles')}}/" + id + "/edit";
  //           var rdelete = "{{url('vehicles')}}/delete/" + id;
  //           var link1 = ' <a class="text-info" href="' + edit + '">Edit</a><br>';
  //           var link2 = ' <a class="text-warning"  href="' + view + '">View</a> <br>';
  //           var link3 = ' <a class="text-danger" href="' + rdelete + '">Delete</a> <br>';
  //           var actionLinks = (sno++) + '<span class="action_wapper">' + link1 + link2 + link3 + '</span>';
  //           return actionLinks;
  //         }
  //       },
  //       {
  //         "data": 'name'
  //       }

  //     ]
  //   });


  //   // Show Action Buttons on row hover
  //   $("#manage_vehicles_classes").mousemove(function(e) {

  //     var left = e.target.getBoundingClientRect().left;
  //     var x = (e.pageX - this.offsetLeft) - 400;

  //     if (x > -10) {
  //       $('.action_wapper').css({
  //         "left": x + "px"
  //       });
  //     }

  //   });
  //   // end of document ready
   });
</script>

@endsection