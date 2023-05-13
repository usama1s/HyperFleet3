
@php
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Staff;
$layout = (Auth::user()->role == '4') ? 'auth.driver.layouts.app' : 'layouts.app';
@endphp

@extends($layout)


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

      <div class="col-md-9">

        <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Read Message</h3>

              
            </div>

           
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-read-info">
                <h5>Message {{ $message->subject}}</h5>


                @if ($_GET['type'] == "Sent Box")
                <h6>To: {{ User::getFullName($message->receiver) }} < {{ User::getEmail($message->receiver) }} >  
                 @php
                      $sender = $message->receiver;
                 @endphp
                @else
                <h6>From: {{ User::getFullName($message->sender) }} < {{ User::getEmail($message->sender) }} >
                  @php
                  $sender = $message->sender;
             @endphp
                @endif

                  <span class="mailbox-read-time float-right">{{ $message->created_at }}</span></h6>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"  onclick="window.location ='{{route('message.delete',$message->id.'?type='.$_GET['type'])}}'" data-container="body" title="Delete">
                    <i class="far fa-trash-alt"></i></button>
                  <button type="button" id="reply" class="btn btn-default btn-sm reply-btn" subject="{{ $message->subject}}" sender-id="{{ $sender }}" sender-email ="{{ User::getEmail($sender) }}"  data-toggle="modal" data-target="#composeModal" title="Reply">
                    <i class="fas fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm forward-btn" data-container="body" title="Forward"  data-target="#composeModal"  data-toggle="modal">
                    <i class="fas fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" onclick="print()" title="Print">
                  <i class="fas fa-print"></i></button>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message" id="msg-body">
                <div>
                   {!! html_entity_decode($message->message) !!}
                </div>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-white">
            
            </div>
            <!-- /.card-footer -->
            <div class="card-footer">
              <div class="float-right">
                <button type="button" class="btn btn-default reply-btn"  data-toggle="modal" data-target="#composeModal"><i class="fas fa-reply"></i> Reply</button>
                <button type="button" class="btn btn-default forward-btn"  data-toggle="modal" data-target="#composeModal"><i class="fas fa-share"></i> Forward</button>
              </div>
            <button type="button" class="btn btn-default" onclick="window.location ='{{route('message.delete',$message->id.'?type='.$_GET['type'])}}'"><i class="far fa-trash-alt"></i> Delete</button>
              <button type="button" class="btn btn-default" onclick="print()"><i class="fas fa-print"></i> Print</button>
            </div>
            <!-- /.card-footer -->
          </div>
      
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection

@section('js')

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

        // new msg button
        $('#newcomposebtn').on('click', function (e) {
          $('#to_mail').val(null).trigger('change');
          $("#subject").val("");
          $('#msg').summernote('reset');
          

        });


        //reply button 
        $('.reply-btn').on('click', function (e) {
            var email = $('#reply').attr("sender-email");
            var id = $('#reply').attr("sender-id");
            var subject = $('#reply').attr("subject");
          
          if ($('#to_mail').find("option[value='" + id + "']").length) {
              
              $('#to_mail').val(id).trigger('change');
          }else{
             // Create a DOM Option and pre-select by default
            var newOption = new Option(email, id, true, true);
            // Append it to the select
            $('#to_mail').append(newOption).trigger('change');
          }
          $("#subject").val(subject);
          $('#msg').summernote('reset');


            
        })

        //forward msg
        $('.forward-btn').on('click', function (e) {

          $('#msg').summernote('reset');

           $('#to_mail').val(null).trigger('change');
          $("#subject").val("");
          var html = $("#msg-body").html();
          
          $("#msg").summernote('pasteHTML', html);
            
        });
        
    });


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
                                    
              }
               
            });


 


      
</script>

@endsection