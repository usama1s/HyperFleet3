@php
    $user = Auth()->user();
@endphp
@extends('layouts.app')

@section('title', 'Keys')

@section('breadcrumb')

<div class="page_sub_menu">

</div>
@endsection

@section('content')
    
@if ($user->role==3)
    <div class="row ">
     
          
      
        {{-- first col --}}
        <div class="col-md-4">
            @if (is_null($key))
            <form action="{{route('apikey.store')}}" method="post">
                @csrf

            <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title">Generate New Api Key</h3>
                </div>

                <div class="card-body">
                    
                    <div class="d-flex justify-content-end mt-2">
                        <input class="btn btn-success" type="submit" value="Generate Key" >
                    </div>
                </div>

            </div>

            </form>
               
        @else
            <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title">Api Key</h3>
                </div>

                <div class="card-body">
                    <label for="">Api Key</label>
                    
                    <input id="apikey" class="form-control" value="{{$key}}" type="text" disabled>
                    <h4>Copy This Link to URL</h4>
                    <textarea  rows="3" cols="50" class="form-control"><iframe src="{{ route('embed',$key) }}"></iframe></textarea>
                </div>

            </div>
            <div class="col-md-12">
                
            </div>

        </div>

        <div class="col-md-8">

            <label for="">
                <strong>API Form Preview</strong>
            </label>
            <iframe class="api-form-preview" src="{{ route('embed',$key) }}"></iframe>

        </div>
        @endif
    
    </div> 
    {{-- row ends --}}
    @else

    {{-- @include('pages/apis/allkeys') --}}

    <table id="allkeys" style="width:100% background:#fff;" class="display table table-hover responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Supplier Name</th>
                
                <th>key</th>
            </tr>
        </thead>
        <tbody>
            @php
            $sno =1;
            @endphp
            @foreach ($keys as $key)
                
            
                <tr>
                    <td>
                        {{ $sno++}}
        
                    </td>
                    <td>{{$key->first_name}} {{$key->last_name}}</td>
                    
                
                    <td>{{$key->key}}</td>
                </tr>

            @endforeach
        </tbody>
    </table>
    
@endif
    


@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#allkeys').DataTable({
            
        });

    });


</script>

@endsection