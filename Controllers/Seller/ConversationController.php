<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use App\Models\ProductQuery;
use Auth;
use Intervention\Image\Facades\Image;
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
            $conversations = Conversation::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            
         return view('seller.conversations.index', compact('conversations'));
        } else {
           
         return back()->with(['error'=>tran('Conversation is disabled at this moment')]);
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
       
        return view('seller.conversations.show', compact('conversation'));
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
        
     
        return view('front.includes.partials_messages', compact('conversation'));
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
                return "Only WAV files are allowed.";
            }
            // Specify the destination path within the public folder
            $destinationPath = public_path('messages_voices');
            // Generate a unique file name
            $fileName = uniqid() . '.' . $extension;

            // Move the uploaded file to the destination path
            $file->move($destinationPath, $fileName);

            // Get the public URL of the uploaded file
            $publicUrl = asset('public/messages_voices/' . $fileName);
            $resp["file"]='messages_voices/'.$fileName;
            $resp["url"]=$publicUrl;
            return $resp;
        }

        return "No file was uploaded.";
    
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
