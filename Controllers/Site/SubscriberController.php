<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Mail\EmailManager;
use Mail;
class SubscriberController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_subscribers'])->only('index');
        $this->middleware(['permission:delete_subscriber'])->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
        return view('admin.subscribers.index', compact('subscribers'));
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
        $subscriber = Subscriber::where('email', $request->email)->first();
        if($subscriber == null){
            $subscriber = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->save();
            $msg=tran('You have subscribed successfully');
        }
        else{
        
            $msg=tran('You are  already a subscriber');
        }
        return back()->with(["success"=>  $msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        Subscriber::destroy($id);
        return redirect()->route('subscribers.index')->with(["success"=>tran('Subscriber has been deleted successfully')]);
    }

    public function testEmail(Request $request){
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return back()->with(['success'=>tran('An email has been sent.')]);
    }
}
