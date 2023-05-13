<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Str;
use App\Events\MessageEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Notifications\CustomNotification;

class MessageController extends Controller
{
    private  $LABEL_SENT = "Sent Box";
    private $LABEL_INBOX = "Inbox";

    public function __construct()
    {
        //create permission
        $this->middleware('auth');

    }

    public function index(Request $request)
    {

        if($request->type == "sent"){

            $messages = Message::whereSender(Auth::user()->id)
            ->where("sender_del", '!=', 1)
            ->latest()
            ->paginate(10);
            $page_name = $this->LABEL_SENT;

        }else{
            $messages = Message::whereReceiver(Auth::user()->id)
            ->where("receiver_del", '!=', 1)
            ->latest()
            ->paginate(10);
            $page_name = $this->LABEL_INBOX;
        }

        return view("pages.message.index",compact('messages','page_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $to = $request->to;
        $subject = $request->subject;
        $msg = htmlentities($request->msg);



        $newMsg = new Message;

        $newMsg->sender = Auth::user()->id;
        $newMsg->receiver =$to;
        $newMsg->subject =$subject;
        $newMsg->message = $msg;

        $user =User::find($to);
        $newMsg->save();

        // $notification = array(
        //     'subject' => Auth::user()->first_name ." ". Auth::user()->last_name,
        //     'msg' => $subject,
        //     'link' => route('message.index'),
        //     'type' => 'newmessage',
        //     "booking_id" => $newMsg
        // );

        // $user->notify(new CustomNotification($notification));

        $senderUser = User::find($newMsg->sender);

        switch ($senderUser->role) {
            case '1':
                # for admin
                $img_path = config('app.logo') ;
                break;
            case '2':
                # for staff
                $img_path = asset('public/assets/staff').'/'.$senderUser->staff->image;
                break;

            case '3':
                # for supplier
                $img_path = asset('public/storage/assets/suppliers').'/'.$senderUser->supplier->image;
                break;

            case '4':
                # for driver
                $img_path = asset('public/assets/drivers').'/'.$senderUser->driver->driver_image;
                break;

            default:
                $img_path = asset('public/images/default-user.jpg');
                break;
        }

        $msg_event = array(
            "id" => $newMsg->id,
            "sender" =>  User::getFullName($newMsg->sender),
            "receiver_id" => $newMsg->receiver,
            "message" => Str::limit(strip_tags(html_entity_decode($newMsg->message)),50,'...'),
            "subject" => $newMsg->subject,
            "show_link" => route('message.show',$newMsg->id)."?type=".$this->LABEL_INBOX,
            'img_path' => $img_path

        );

        event(new MessageEvent($msg_event));

        return back()->with("success","Message sent");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message, Request $request)
    {


        if( $request->type == $this->LABEL_INBOX){
            $message->is_read = 1;
             $message->save();
        }
        return view('pages.message.view',compact('message'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Message $message)
    {


        if( $request->type ==$this->LABEL_SENT){
            $message->sender_del = 1;
        }

        if( $request->type == $this->LABEL_INBOX){
            $message->receiver_del = 1;
        }

        if(($message->receiver_del == 1) && ($message->sender_del == 1) ){
            $message->delete();
        }else{
            $message->save();
        }

        return redirect(route('message.index'))->with("success"," Message deleted");
    }

    public function bulkdestroy(Request $request){
        if(!is_null($request->seleted_id)){


                $counter = 0;
                $messages = Message::whereIn("id",$request->seleted_id)->get();
                foreach($messages as $message){

                    if( $request->type ==$this->LABEL_SENT){
                        $message->sender_del = 1;
                    }

                    if( $request->type == $this->LABEL_INBOX){
                        $message->receiver_del = 1;
                    }

                    if(($message->receiver_del == 1) && ($message->sender_del == 1) ){
                        $message->delete();
                    }else{
                        $message->save();
                    }
                    $counter++;

                }

                return back()->with("success", $counter." messages deleted");

        }else{
            return back()->with("error","please select message for deleting");
        }

    }
}
