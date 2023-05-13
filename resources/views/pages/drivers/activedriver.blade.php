@extends('layouts.app')

@section('title', 'Active Drivers')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
  <li class="breadcrumb-item active">Active Drivers</li>
    
</ol>

@endsection  

@section('content')

<table id="active_drivers" style="width:100%" class="table responsive">
  <thead>
      <tr>

        <th>Name</th>
        <th>Email</th>
        <th>User Type</th>
      </tr>
  </thead>
<tbody>

  @foreach ($users as $user)
  <tr>
      <td>{{$user->first_name}} {{$user->last_name}}</td>
      <td>{{$user->email}}</td>

      @if ($user->role==1)
      <td>Admin</td>

      @elseif($user->role==2)
        <td>Staff</td>

      @elseif($user->role==3)
        <td>Supplier</td>

      @elseif($user->role==4)
        <td>Driver</td>

      @elseif($user->role==5)
        <td>Customer</td>
      
        @else <td>Unknown User</td>
      @endif
      
  </tr>
  @endforeach

</tbody>
<tr>
  <td colspan="10">
      {{ $users->links() }}
  </td>
</tr>
</table>
@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {
        $('#active_drivers').DataTable({
            paging:false
        });

      });

      </script>

@endsection