@php
    use App\Models\User;
    $vehicle_class_prev_form_type = session('voucher_prev_form_type');
    session()->forget('voucher_prev_form_type');
@endphp
@extends('layouts.app')

@section('title', 'Vouchers Manage')

@section('breadcrumb')

<div class="page_sub_menu">
    {{-- @can('shift-create') --}}
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#newVoucherForm">Add New</button>
    {{-- @endcan --}}
</div>
@endsection

@section('content')

<!-- Modal for New Voucher Form -->
<div class="modal fade" id="newVoucherForm" tabindex="-1" role="dialog" aria-labelledby="newVoucherFormLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('vouchers.store')}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newVoucherFormLabel">Add New Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row col-md-12">

                        <label for="add-new-input">Voucher Code</label>
                        <input class="form-control" type="text" name="code" id="voucher_code" placeholder="Voucher Code"
                             required autocomplete="off" value="{{ old('code') }}">
                        @error('code')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                             

                    </div>


                    <div class="row" style="width: 100%;">
                   
                        <div class="form-group col-md-6">
                            <label for="add-new-input">Voucher Type</label>

                            <select class="form-control" name="voucher_type" id="voucher_type" required>
                                
                                <option value="" {{(old('voucher_type') == '') ? 'selected' : ''}}>Select Voucher Type</option>
                                <option value="flat" {{(old('voucher_type') == 'flat') ? 'selected' : ''}}>Flat</option>
                                <option value="percentage" {{(old('voucher_type') == 'percentage') ? 'selected' : ''}}>Percentage</option>

                            </select>
                            @error('voucher_type')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    
                        <div class="form-group col-md-6" >
                            <label for="add-new-input">Discount:</label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="discount" id="discount" placeholder="Discount"
                                required autocomplete="off" value="{{ old('discount') }}" aria-label="Amount (to the nearest dollar)">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="discount-symbol">%</span>
                                </div>
                                @error('discount')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    
                    </div>

                    <div class="row col-md-12">

                        <label for="add-new-input">Description</label>
                        <input class="form-control" type="text" name="description" id="Description" placeholder="Description"
                              autocomplete="off" value="{{ old('description') }}">
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
<form action="{{ route("vouchers.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_vouchers" style="width:100% background:#fff;" class="table responsive table-hover">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Voucher Code</th>
            <th>Voucher Type</th>
            <th>Discount</th>
            <th>Description</th>
            <th>Created By</th>
            
            <noscript>
                <th>Actions</th>
            </noscript>

        </tr>
    </thead>
    <tbody>

        @php
        $sno =1;
        @endphp

        @foreach ($vouchers as $voucher)
        <tr>
           <td style="width:20px;">
        <label><input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $voucher->id }}"></label></td>
            <td>
                {{ $sno++}}

            </td>

            <td> {{ $voucher->code}}</td>
     
            <td>{{ $voucher->type }}
                <span class="action_wapper2">
               
                    <a class="text-success mr-2" data-toggle="modal" data-voucher="{{ $voucher->id }}" data-target="#newVoucherEditForm" class="edit_link"
                        data-voucher-code = "{{ $voucher->code }}" data-voucher-type="{{ $voucher->type }}" data-voucher-description="{{ $voucher->description }}"
                        data-voucher-discount="{{ $voucher->discount }}"
                        href="{{url('vouchers')}}/{{$voucher->id}}/edit"><i
                        class="fa fa-edit" title="edit" data-toggle="tooltip" data-placement="top"></i></a>|
    
                    <a class="text-danger ml-2"  onclick="(function(e){e.preventDefault();record_delete(e)})(event)" title="delete" data-toggle="tooltip" data-placement="top" href="{{url('vouchers')}}/delete/{{$voucher->id}}"><i
                        class="fa fa-trash"></i></a>
                
                </span>
            </td>
           
            <td>{{ $voucher->discount }}</td>
        <td>
        
            @if (!is_null($voucher->description))
                {{ $voucher->description}}
            @else 
                NA
            @endif
        </td>
            <td>{{User::getFullName($voucher->creater_id)}}</td>

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
                    {{-- <a class="btn btn-sm btn-success" href="{{url('vouchers')}}/{{$voucher->id}}/edit">Edit</a> --}}
                    <a class="btn btn-sm btn-danger" href="{{url('vouchers')}}/delete/{{$voucher->id}}">Delete</a>
                </td>
            </noscript>
        </tr>

        @endforeach

    </tbody>
    @if($vouchers->hasPages())
    <tr>
        
            <td colspan="7">
                {{ $vouchers->links() }}
            </td>
     
    </tr>
    @endif
</table>
</form>

{{-- edit modal --}}


<div class="modal fade" id="newVoucherEditForm" tabindex="-1" role="dialog" aria-labelledby="newVoucherEditFormLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="voucher_edit" action="">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newVoucherEditFormLabel">Edit Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row col-md-12">

                        <label for="add-new-input">Voucher Code</label>
                        <input class="form-control" type="text" name="code" id="voucherCode" 
                            required autocomplete="off">
                        @error('code')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    </div>

                    <div class="row" style="width: 100%;">
                   
                        <div class="form-group col-md-6">
                            <label for="add-new-input">Voucher Type</label>

                            <select class="form-control" name="voucher_type"  id="edit_voucher_type" required>
                                <option value="">Select Voucher Type</option>

                                <option value="flat" >Flat</option>
                                <option value="percentage">Percentage</option>

                            </select>
                            @error('voucher_type')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                 
                        <div class="form-group col-md-6">

                            <label for="add-new-input">Discount:</label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="discount" id="voucherDiscount"
                                required autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="edit-discount-symbol">%</span>
                                </div>
                                @error('discount')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                        </div>
                 
                    </div>

                

                    <div class="row col-md-12">

                        <label for="add-new-input">Description</label>
                        <input class="form-control" type="text" name="description" id="voucherDescription" 
                              autocomplete="off" required>
                   
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

@endsection


@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        var table = $('#manage_vouchers').DataTable({
            paging:false
        });
        if('{{ count($vouchers) }}' < 1){
            $("#thcheck").hide();
           
        }
        

   

    if('{{ $errors->any() }}'){
        $("#newVoucherForm").modal('show')
    }


    

    $('#newVoucherEditForm').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var voucher_id = button.data('voucher')
        var voucherCode = button.data('voucher-code')
        var voucherType = button.data('voucher-type')
        var voucherDescription = button.data('voucher-description')
        var voucherDiscount = button.data('voucher-discount')

       
        var modal= $(this)

        modal.find('.modal-body #voucher_id').val(voucher_id)
        modal.find('.modal-body #voucherCode').val(voucherCode)
        modal.find('.modal-body #voucherType').val(voucherType)        
        modal.find('.modal-body #voucherDescription').val(voucherDescription)
        modal.find('.modal-body #voucherDiscount').val(voucherDiscount)
      
      
    $('#voucher_edit').attr("action", "{{route('vouchers.index')}}" + "/" + voucher_id);
   
    });


    $("#voucher_type").change(function(){

        var value = $(this).val()
       
            if(value=="flat"){
                
                $("#discount-symbol").text('{{ Config("currency-symbol")}}');
               
            }else{
                $("#discount-symbol").text('%');
            }
    });

    $("#edit_voucher_type").change(function(){

        var value = $(this).val()

            if(value=="flat"){
                
                $("#edit-discount-symbol").text('{{ Config("currency-symbol")}}');
            
            }else{
                $("#edit-discount-symbol").text('%');
            }
        });
    
});

</script>

@endsection