@php
$user = Auth::user();
@endphp
<li class="nav-item dropdown mr-3 mt-2">
<a class="nav-link" data-toggle="dropdown" href="#">
<i class="far fa-bell"></i>
<span class="badge badge-warning navbar-badge notification_count">{{ ($user->unreadNotifications->count() > 0) ? $user->unreadNotifications->count() : '' }}</span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="nofifications">
<span class="dropdown-item dropdown-header"><span class="notification_count">
    {{ ($user->unreadNotifications->count() > 0) ? $user->unreadNotifications->count() : '' }}</span> Notifications   <a id="mark-as-unread" class="text-right float-right">Mark as unread</a></span>

<div id="nofifications_wapper">
@foreach($user->notifications  as $notification)
  <div class="dropdown-divider"></div>
<a href=" {{$notification->data["link"]}}" class="dropdown-item single-notification {{ is_null($notification->read_at) ? 'unread_notification' : 'read_notification' }}">
    <i class="fas fa-envelope mr-2"></i> {{$notification->data["subject"]}}  <span class="float-right text-muted text-sm">{{$notification->created_at->diffForHumans()}}</span>
  </a>
@endforeach
</div>

</li>


    



