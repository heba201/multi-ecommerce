<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class NotificationController extends Controller
{
    public function index() {
        $notifications = auth()->user()->notifications()->paginate(15);
        
        auth()->user()->unreadNotifications->markAsRead();
        
        if(Auth::user()->user_type == 'admin') {
            return view('admin.notification.index', compact('notifications'));
        }
        
        if(Auth::user()->user_type == 'seller') {
            return view('seller.notification.index', compact('notifications'));
        }
        
        if(Auth::user()->user_type == 'customer') {
            return view('front.user.notification', compact('notifications'));
        }
        
    }
}
