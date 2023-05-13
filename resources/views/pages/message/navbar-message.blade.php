@php
use App\Models\Message;
use Illuminate\Support\Str;
use App\Models\User;
     $messages = Message::whereReceiver(Auth::user()->id)
            ->where("receiver_del", '!=', 1)
            ->where("is_read", '!=', 1)
            ->latest()
            ->get();
@endphp
<li class="nav-item dropdown mr-2 mt-2">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-comments"></i>
      <span class="{{ App\Models\Message::CountUnRead() ? 'badge badge-danger navbar-badge' : '' }}" id="unread_msg_count_navbar">{{App\Models\Message::CountUnRead() }}</span>
    </a>
    <div id="nav-bar-message-container" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

      <a href="{{ route('message.index','newmessage=true')}}" class="dropdown-item bg-info text-center"><i class="fas fa-pencil-alt"></i> New message</a>

        @forelse ($messages as $message)

        <a href="{{route('message.show',$message->id.'?type=Inbox')}}" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
                @php
                    $user = User::find($message->sender);

                    switch ($user->role) {
                        case '1':
                            # for admin
                            $img_path = config('app.logo') ;
                            break;
                        case '2':
                            # for staff
                            $img_path = asset('public/assets/staff').'/'.$user->staff->image;
                            break;
                        
                        case '3':
                            # for supplier
                            $img_path = asset('public/storage/assets/suppliers').'/'.$user->supplier->image;
                            break;
                        
                        case '4':
                            # for driver
                            $img_path = asset('public/assets/drivers').'/'.$user->driver->driver_image;
                            break;
                        
                        default:
                            $img_path = asset('public/images/default-user.jpg');
                            break;
                    }
                @endphp
            <img src="{{ $img_path }}" alt="profile-img" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                    {{ User::getFullName($message->sender) }}
                  
                </h3>
                <p class="text-sm"> {!! Str::limit(strip_tags(html_entity_decode($message->message)),10 , '...') !!}</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>{{ $message->created_at->diffForHumans() }}</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
            
        @empty
           <p class="text-center mt-2 mb-2">No Message</p> 
        @endforelse
     
     
      
      <a href="{{ route('message.index')}}" class="dropdown-item dropdown-footer">See All Messages</a>
    </div>
  </li>