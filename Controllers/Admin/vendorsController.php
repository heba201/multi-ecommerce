<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\vendorrequest;
use App\Models\maincategory;
use App\Models\vendor;
use Illuminate\Support\Facades\Notification;
use  App\Notifications\vendorcreated;
use Illuminate\Http\Request;

class vendorsController extends Controller
{
    //
    public function index()
    {
        $vendors = vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }
    public function create()
    {
        $categories = maincategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }
    public function store(vendorrequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'logo' => $filePath,
                'password' => $request->password,
                'category_id' => $request->category_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            Notification::send($vendor, new vendorcreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

}
