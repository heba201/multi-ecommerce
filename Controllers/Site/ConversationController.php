<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use App\Models\Product;
use App\Http\Requests\MessageRequest;
use App\Utility\NotificationUtility;
use Auth;
use Mail;

use Illuminate\Support\Facades\Storage;
use App\Mail\ConversationMailManager;
class ConversationController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(8);
            return view('front.user.conversations.index', compact('conversations'));
        } else {
            return back()->with(['warning'=>tran('Conversation is disabled at this moment')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_index()
    {
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::orderBy('created_at', 'desc')->get();
            return view('admin.support.conversations.index', compact('conversations'));
        } else {
            return back()->with(["warning"=>tran('Conversation is disabled at this moment')]);;
        }
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
  
     public function uploadvoice(Request $request)
     {
        if ($request->hasFile('audio')) {
            $resp["file"]="";
            $resp["url"]="";
            $file = $request->file('audio');

            // Validate the file type
            $extension = $file->getClientOriginalExtension();
            if ($extension !== 'wav') {
                return  tran("Only WAV files are allowed.");
            }
            // Specify the destination path within the public folder
            $destinationPath = ('messages_voices');
            // Generate a unique file name
            $fileName = uniqid() . '.' . $extension;

            // Move the uploaded file to the destination path
            $file->move($destinationPath, $fileName);

            // Get the public URL of the uploaded file
            $publicUrl = asset('messages_voices/' . $fileName);
            $resp["file"]='messages_voices/'.$fileName;
            $resp["url"]=$publicUrl;
            return $resp;
        }

        return  tran("No file was uploaded.");
    
     }
     public function store(MessageRequest $request)
    {
        $user_type = Product::findOrFail($request->product_id)->user->user_type;
        $conversation = new Conversation;
        $conversation->sender_id = Auth::user()->id;
        $conversation->receiver_id = Product::findOrFail($request->product_id)->user->id;
        $conversation->title = $request->title;

        if ($conversation->save()) {
            $filePath = "";
        if ($request->has('file')) {
            $filePath = uploadImage('messages_photos', $request->file);
        }
            $message = new Message;
            $message->conversation_id = $conversation->id;
            $message->user_id = Auth::user()->id;
            $message->message =$request->url.'<br>'.$request->message;
            $message->voice_message=$request->voice;
            $message->message_photo=$filePath;
            if ($message->save()) {
                $this->send_message_to_seller($conversation, $message, $user_type);
            }
        }
        NotificationUtility::sendNotification_conversation($conversation);
        
        return back()->with(["success"=>tran('Message has been sent to seller')]);
    }

    public function send_message_to_seller($conversation, $message, $user_type)
    {
        $array['view'] = 'emails.conversation';
        $array['subject'] = tran('Sender').':- '. Auth::user()->name;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = tran('Hi! You recieved a message from ') . Auth::user()->name . '.';
        $array['sender'] = Auth::user()->name;

        if ($user_type == 'admin') {
            $array['link'] = route('conversations.admin_show', encrypt($conversation->id));
        } if($user_type == 'customer'){
            $array['link'] = route('customer.conversations.show', encrypt($conversation->id));
        }
        if ($user_type == 'seller') {
            $array['link'] = route('conversations.show', encrypt($conversation->id));
        }

        $array['details'] = $message->message;

        try {
            // Mail::to($conversation->receiver->email)->queue(new ConversationMailManager($array));
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        } elseif ($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();
        return view('front.user.conversations.show', compact('conversation'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $conversation = Conversation::findOrFail(decrypt($request->id));
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
            $conversation->save();
        } else {
            $conversation->receiver_viewed = 1;
            $conversation->save();
        }
        if($conversation->sender_id == Auth::user()->id || $conversation->receiver_id == Auth::user()->id){
        return view('front.includes.partials_messages', compact('conversation'));
        } else  return view('front.includes.admin_partials_messages_onlyshow', compact('conversation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_show($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        } elseif ($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();
        if($conversation->sender_id == Auth::user()->id || $conversation->receiver_id == Auth::user()->id){
            return "1";
            // return view('admin.support.conversations.show', compact('conversation'));
        }
        else {
           // return "2";
             return view('admin.support.conversations.onlyshow', compact('conversation'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        $conversation->messages()->delete();

        if (Conversation::destroy(decrypt($id))) {
            return back()->with(["success"=>tran('Conversation has been deleted successfully')]);
        }
    }


    public function message_store(MessageRequest $request)
    {
        $filePath = "";
        if ($request->has('file')) {   
                $filePath = uploadImage('messages_photos', $request->file);
                    clearstatcache();
                  
        }
      
        $message = new Message;
        $message->conversation_id = $request->conversation_id;
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        $message->voice_message=$request->voice;
        $message->message_photo=$filePath;
        $message->save();
    
        $conversation = $message->conversation;
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->receiver_viewed = "1";
        } elseif ($conversation->receiver_id == Auth::user()->id) {
            $conversation->sender_viewed = "1";
        }
        $conversation->save();

         return back();
    }
}
