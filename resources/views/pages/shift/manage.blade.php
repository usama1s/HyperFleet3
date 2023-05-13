@php
use App\Models\VehicleClass;
use App\Models\Driver;
use App\Models\Supplier;
$supplier = new Supplier;

$shift_prev_form_type = session('shift_prev_form_type');
  session()->forget('shift_prev_form_type');

 //dd($shift_prev_form_type );

$driver = new Driver;
@endphp

@extends('layouts.app')

@section('title', 'Manage Shifts')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('shift-delete')
        <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    @endcan
    @can('shift-create')
        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#newShiftForm">Add New</button>
    @endcan
</div>
@endsection

@section('content')
@if (auth()->user()->role==1)
<div>
    <form action="{{route('categories.shift')}}" method="get" id="selectSupplierForm">
   
        <div class="row">

            <div class="col-md-4 form-group">
                
                <a href="{{route('shift.index')}}" class="btn btn-lg btn-info" id="allShift" style="width: 100%;margin-top: 24px;">All Shifts</a>
            </div>

            <div class="col-md-4 form-group">
                
                <a href="{{route('categories.shift',"supplier_id=")}}" class="btn btn-lg btn-info" style="width: 100%;margin-top: 24px;">Own Shifts</a>
            </div>

            <div class="col-md-4">
                <label for="">Show Shifts By</label>
                <select name="supplier_id" class="form-control by-driver" id="selectSupplier">  
                    <option value="">Select Supplier</option>

                    @forelse ($supplier::all() as $i)
                        @if (@$_GET['supplier_id'] == $i->user_id)
                            <option value="{{$i->user_id}}" selected>{{$supplier->fullName($i->user_id)}}</option>
                        @else
                            <option value="{{$i->user_id}}" >{{$supplier->fullName($i->user_id)}}</option>
                        @endif
               
                    @empty
                        <option value="">No supplier Found</option>
                    @endforelse
               
                </select>
            </div>
            
        </div>
    </form>
</div>
@endif
<!-- Modal for New Shift Form -->
<div class="modal fade" id="newShiftForm" tabindex="-1" role="dialog" aria-labelledby="newShiftFormLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('shift.store')}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newShiftFormLabel">Add New Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="add-new-input">Shift Name:</label>
                    <input class="form-control" type="text" name="name" id="shift_name" placeholder="Morning ,Evening" required
                         autocomplete="off">
                        @error('name')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    <div class="row">
                        <div class="col-md-6">
                            <label for="add-new-input">Shift Start:</label>
                            <input class="form-control datetimepicker-input" type="text" name="start" id="shift_start" required placeholder="Shift Start Time"
                                data-toggle="datetimepicker" data-target="#shift_start"  autocomplete="off">
                                @error('start')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="add-new-input">Shift End:</label>
                            <input class="form-control datetimepicker-input" type="text" name="end" id="shift_end" required placeholder="Shift End Time"
                                data-toggle="datetimepicker" data-target="#shift_end"  autocomplete="off"> 
                                @error('end')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror

                                @error('invaild')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for edit Shift Form -->
<div class="modal fade" id="newShiftEditForm" tabindex="-1" role="dialog" aria-labelledby="newShiftEditFormLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="shift_edit" action="">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newShiftEditFormLabel">Edit Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="add-new-input">Shift Name:</label>
                    <input class="form-control" type="text" name="name" id="shiftName" placeholder="Morning ,Evening" 
                        required autocomplete="off" value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    <div class="row">
                        <div class="col-md-6">
                            <label for="add-new-input">Shift Start:</label>
                            <input class="form-control datetimepicker-input" type="text" name="start" id="shiftStart" 
                                data-toggle="datetimepicker" data-target="#shiftStart" required autocomplete="off" value="">
                                @error('start')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="add-new-input">Shift End:</label>
                            <input class="form-control datetimepicker-input" type="text" name="end" id="shiftEnd"
                                data-toggle="datetimepicker" data-target="#shiftEnd" required autocomplete="off" value="">
                                @error('end')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                                
                                @error('invaild')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>


<form action="{{ route("shift.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_shifts" style="width:100% background:#fff;" class="table table-hover">
    <thead>
        <tr>
            <th data-orderable="false" id="thcheck"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Name</th>
            <th>Start</th>
            <th>End</th>
            <noscript>
                <th>Actions</th>
            </noscript>

        </tr>
    </thead>
    <tbody>

        @php
        $sno =1;
        @endphp

        @foreach ($shifts as $shift)
        <tr>
            <td style="width:20px;"> <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $shift->id }}"></td>
            <td>
                {{ $sno++}}

            </td>

            <td>
                {{ $shift->name}}
                <span class="action_wapper2">
                    @can('shift-edit')
                    <a class="text-success mr-2" href="{{url('shift')}}/{{$shift->id}}/edit"  data-toggle="modal" data-target="#newShiftEditForm"
                        data-shift="{{ $shift->id }}" data-shift-name = "{{ $shift->name }}" data-shift-start = "{{ $shift->start }}" data-shift-end = "{{ $shift->end }}"
                     data-placement="top"><i
                        class="fa fa-edit" title="edit" data-toggle="tooltip" data-placement="top"></i></a> |
                    @endcan

                    @can('shift-delete')
                    <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{url('shift')}}/delete/{{$shift->id}}" title="delete" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-trash ml-2"></i></a>
                    @endcan
                </span>
            </td>
            <td>{{ date("g:i a", strtotime($shift->start)) }}</td>
            <td>{{ date("g:i a", strtotime($shift->end)) }}</td>

            <noscript>
                <style>
                    .action_wapper2 {
                        display: none;
                    }

                    .action_wapper {
                        display: none;
                    }
                </style>
                <td>
                    <a class="btn btn-sm btn-success" href="{{url('shift')}}/{{$shift->id}}/edit">Edit</a>
                    <a class="btn btn-sm btn-danger" href="{{url('shift')}}/delete/{{$shift->id}}">Delete</a>
                </td>
            </noscript>
        </tr>
        @endforeach

    </tbody>
</table>
</form>
@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {

        if('{{ $shift_prev_form_type }}' == "add-new-form"){
      $('#newShiftForm').modal('show')
      
     }

     if('{{ $shift_prev_form_type }}' == "update-form"){
      $('#newShiftEditForm').modal('show')
     }

        $("#selectSupplier").select2();

        $("#allShift").on("click",function(){
 
            $("#allShift").addClass('active');
        })

        $("#selectSupplier").on("change",function(){
           
            $("#selectSupplierForm").submit();
        })

        $('[data-toggle="tooltip"]').tooltip(); 
        var table = $('#manage_shifts').DataTable({});

        if('{{ count($shifts) }}' < 1){
            $("#thcheck").hide();
        }

        
       var d = $('#shift_start , #shiftStart').datetimepicker({
            format: 'LT'
        });

        
        $('#shift_end , #shiftEnd').datetimepicker({
            format: 'LT'
        });
        // new form
        $('#newShiftForm').on('show.bs.modal', function (event) {

   

        var modal= $(this)

        modal.find('.modal-body #shift_id').val('')
        modal.find('.modal-body #shift_name').val('')
        modal.find('.modal-body #shift_start').val('')
        modal.find('.modal-body #shift_end').val('')



      

        })

        //Edit Form
        $('#newShiftEditForm').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget) // Button that triggered the modal
            var shift_id = button.data('shift')
            var shiftName = button.data('shift-name')
            var shiftStart = button.data('shift-start')
            var shiftEnd = button.data('shift-end')
         


            var modal= $(this)

            modal.find('.modal-body #shift_id').val(shift_id)
            modal.find('.modal-body #shiftName').val(shiftName)
            //  modal.find('.modal-body #shiftStart').val(shiftStart)
            modal.find('.modal-body #shiftEnd').val(shiftEnd)

          var pickup_time = shiftStart;
           pickup_time = pickup_time.split(':');


      $('#shiftStart').datetimepicker('destroy');
      $('#shiftStart').datetimepicker({
        format: 'LT',
        date:  moment({
                  hour :pickup_time[0], minute :pickup_time[1]
          })
        });

        var pickup_time = shiftEnd;
           pickup_time = pickup_time.split(':');

        $('#shiftEnd').datetimepicker('destroy');
      $('#shiftEnd').datetimepicker({
        format: 'LT',
        date:  moment({
                  hour :pickup_time[0], minute :pickup_time[1]
          })
        });
    


            $('#shift_edit').attr("action", "{{route('shift.index')}}" + "/" + shift_id);

        })


        // Show Action Buttons on row hover with mouse cursor
        /*     $("#manage_vehicles").mousemove(function(e){
                
                    var left = e.target.getBoundingClientRect().left;
                    var x = (e.pageX - this.offsetLeft)-400; 

                    if(x>-10){
                    
                        $('.action_wapper').css({"left": x+"px", "position": "absolute"});
                    }
              
            }); */

        // show action button without mouse cursor
        /*   $('#manage_vehicles tbody').on( 'mouseenter', 'tr', function (e) {
              var left = e.target.getBoundingClientRect().left;
                  var x = (e.pageX - this.offsetLeft)-400; 

                  if(x>-10){
                  
                      $('.action_wapper').css({"left": x+"px", "position": "absolute"});
                  }
          }); */

        // show inline button on mouse hover
        /*  $('#manage_vehicles tbody').on( 'mouseenter', 'tr', function (e) {
            
         }); */





        // end of document ready
    });
</script>

@endsection