
@php
     use App\Models\User;
  use Illuminate\Support\Str;
  use App\Models\Staff;
@endphp




@php
    $layout = (Auth::user()->role == '4') ? 'auth.driver.layouts.app' : 'layouts.app';
@endphp



@extends($layout)


{{-- @extends('layouts.app') --}}



@section('title', 'Messaging')

@section('breadcrumb')

<div class="page_sub_menu">
  
</div>
@endsection

@section('content')

<section class="content">

  
    <div class="row">
      <div class="col-md-3">
      @include('pages.message.sidebar')
    </div>
    <!-- /.col -->

      <div class="col-md-9">
       
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">{{ $page_name}}</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Search Mail">
                <div class="input-group-append">
                  <div class="btn btn-dark">
                    <i class="fas fa-search"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          
          <div class="card-body p-0">
            
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
              </button>
              <div class="btn-group">
                <button type="button" onclick="document.getElementById('record-form').submit()" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                
              </div>
              <!-- /.btn-group -->
              <button type="button" class="btn btn-default btn-sm" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i></button>
              <div class="float-right">
                {{-- 1-50/200 --}}
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                </div>
                <!-- /.btn-group -->
              </div>
              <!-- /.float-right -->
            </div>
            <form action="{{ route("message.bulkdestroy") }}" method="POST" id="record-form">
            <div class="table-responsive mailbox-messages">
             
                <input type="hidden" name="type" value="{{ $page_name}}">
                @csrf
              <table class="table table-hover">
                <tbody id="message_tbody"> 

                  @forelse ($messages as $message)

                  @php
                      
                  
                 
                    if($page_name !='Sent Box'){
                     
                        $name = User::getFullName($message->sender);

                      
                        if ($message->is_read == 0){
                   
                          $active = "active";

                        }else{
                          $active = "";
                          
                        }
                     
                      
                    }else{
                     
                      $name = User::getFullName($message->receiver);

                    
                      $active = "";
                    }

                 
                 
                  @endphp
                
               
                <tr class="{{$active}}">
                    <td>
                      <div class="icheck-primary">
                      <input type="checkbox" value="{{$message->id}}" name="seleted_id[]" id="check{{$message->id}}">
                        <label for="check{{$message->id}}"></label>
                      </div>
                    </td>
                    <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                  <td class="mailbox-name"><a href="{{route('message.show',$message->id.'?type='.$page_name)}}">{{ $name }}</a></td>
                    <td class="mailbox-subject"><b>{{$message->subject}}</b>
                      {!! Str::limit(strip_tags(html_entity_decode($message->message)),50 , '...') !!}
                    </td>
                    <td class="mailbox-attachment"></td>
                  <td onclick='onc(this)' class="mailbox-date">{{$message->created_at->diffForHumans()}}</td>
                  </tr>

                  @empty
                  <tr>
                    <td colspan="10" class="text-center"> No Message</td>
                  </tr>
                  @endforelse
               
                </tbody>
              </table>
           
              <!-- /.table -->
            </div>
              </form>
      
            <!-- /.mail-box-messages -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer p-0">
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
              </button>
              <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm" onclick="document.getElementById('record-form').submit()"><i class="far fa-trash-alt"></i></button>
               
              </div>
              <!-- /.btn-group -->
              <button type="button" class="btn btn-default btn-sm" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i></button>
              <div class="float-right">
                {{-- 1-50/200 --}}
                <div class="btn-group">
                
                {{-- <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button> --}}
                </div>
                <!-- /.btn-group -->
              </div>
              <!-- /.float-right -->
            </div>
           
          </div>
          @if ($page_name == "Inbox")
          {{ $messages->appends(["type" => 'inbox' ])->links() }}
          @else    
          {{ $messages->appends(["type" => 'sent' ])->links() }}
          @endif
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  </section>




@endsection

@section('js')

<style>
  .select2-selection__choice__remove{
    width: 18px;
    text-align: center;
    background: #1a1d20;
    position: relative;
  }
</style>
<script>
   
    $(document).ready(function() {

               
                var jsonData = JSON.parse(window.messageUser);
                var newuserdata = new Array;
                jsonData.map((index)=>{
                  
                  var name = index.first_name + " " +index.last_name;
                  var email =index.email;
                 
                  switch(index.role){
                    case 1:
                    var role ='Admin';
                    break;

                    case 2:
                    var role ='Staff';
                    break;

                    case 3:
                    var role ='Supplier';
                    break;

                    case 4:
                    var role ='Driver';
                    break;

                    default:
                    var role ='Unknow';
                   
                  }

                  newjson = new Object;
                  newjson.id =index.id;
                  
                   newjson.text = `
                     
                      <div>${name}</div>
                      <div style="display:none;">
                        ${email}
                      <span class="float-right">${role}</span></div>
                   `;

                  //newjson.text = name;

                  newjson.html = `
                  <div>${name}</div>
                  <div style="display:block;">
                    <span class="text-sm" style="color: #5b5b5b">${email}</span> 
                    <span class="float-right">${role}</span></div>
                  `;

                  newjson.title = name;

                  

                  newuserdata.push(newjson);

                });

          



        $('#msg').summernote({
          disableGrammar: true,
          height: 250
        });

        var sel = $("#to_mail").select2({
            placeholder: 'To',
            data: newuserdata,
            escapeMarkup: function(markup) {
              return markup;
            },
            templateResult: function(data) {
              return data.html;
            },
            templateSelection: function(data) {
              return data.text;
            }
        });

        
  

        var pre_style= sel.siblings("span").attr("style")
        var new_style = pre_style +" flex:1;";
        sel.siblings("span").attr("style",new_style);
       
    });

    function removeMsgTo(input){
      console.log(input)
    }


      
</script>

<script>



    $(function () {

      if('{{(isset($_GET["newmessage"]) && $_GET["newmessage"])?$_GET["newmessage"]:''}}' =="true"){
        $("#composeModal").modal('show');
      }else{
        console.log("false");
      }

    
      //Enable check and uncheck all functionality
      $('.checkbox-toggle').click(function () {
        var clicks = $(this).data('clicks')
        
        if (clicks) {
          //Uncheck all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
          $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
          //Check all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
          $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
      })
  
      //Handle starring for glyphicon and font awesome
      $('.mailbox-star').click(function (e) {
        e.preventDefault()
        //detect type
        var $this = $(this).find('a > i')
      
        var glyph = $this.hasClass('glyphicon')
        var fa    = $this.hasClass('fas')
  
        //Switch states
        if (glyph) {
          $this.toggleClass('glyphicon-star')
          $this.toggleClass('glyphicon-star-empty')
        }
  
        if (fa) {
          $this.toggleClass('fa-star')
          $this.toggleClass('fa-star-o')
        }
      })
    })

    var unread_count = document.getElementById("unread_msg_count");
    var count = parseInt(unread_count.innerText);
    if(isNaN(count)){
      count = 0;
    }
    

    Echo.private('notifyChannel')
            .listen('MessageEvent', (event) => {
                
             var  msg = event.message;

              if(msg.receiver_id =='{{ Auth::user()->id}}'){
                count = count +1;
                unread_count.innerText =count;
                unread_count.className  = "badge bg-danger float-right";

              console.log(unread_count);
                          
                var new_tr = `
                <tr class="active">
                    <td>
                      <div class="icheck-primary">
                      <input type="checkbox" value="${msg.id}" name="seleted_id[]" id="check${msg.id}">
                        <label for="check${msg.id}"></label>
                      </div>
                    </td>
                    <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                  <td class="mailbox-name"><a href="${msg.show_link}">${msg.sender}</a></td>
                    <td class="mailbox-subject"><b>${msg.subject}</b>
                      ${msg.message}
                    </td>
                    <td class="mailbox-attachment"></td>
                  <td onclick='onc(this)' class="mailbox-date">1 sec ago</td>
                  </tr>
                `;

                 
                $("#message_tbody").prepend(new_tr);
              }
               
            });

    

  
  </script>

@endsection