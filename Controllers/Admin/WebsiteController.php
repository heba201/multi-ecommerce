<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:header_setup'])->only('header');
        $this->middleware(['permission:footer_setup'])->only('footer');
        $this->middleware(['permission:view_all_website_pages'])->only('pages');
        $this->middleware(['permission:website_appearance'])->only('appearance');
    }

	public function header(Request $request)
	{
		return view('admin.website_settings.header');
	}
	public function footer(Request $request)
	{	
		$lang = $request->lang;
		return view('admin.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		return view('admin.website_settings.pages.index');
	}
	public function appearance(Request $request)
	{
		return view('admin.website_settings.appearance');
	}
}
