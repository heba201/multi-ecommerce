<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\languagerequest;
use App\Models\language;
use Illuminate\Support\Facades\Lang;
class languagesController extends Controller
{
    //
    public function index()
    {
        $languages = language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));
    }
    public function create()
    {
        return view('admin.languages.create');
    }
    public function store(languagerequest $request)
    {
        try {
            if (!$request->has('active'))
            $request->request->add(['active' => 0]);
            language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    
    public function edit($id)
    {
        $language = language::select()->find($id);
        if (!$language) {
            return redirect()->route('admin.languages')->with(['error' => 'هذه اللغة غير موجوده']);
        }

        return view('admin.languages.edit', compact('language'));
    }
    public function update($id, languagerequest $request)
    {

        try {
            $language = language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }


            if (!$request->has('active'))
                $request->request->add(['active' => 0]);

            $language->update($request->except('_token'));

            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function destroy($id)
    {

        try {
            $language = language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $language->delete();

            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

}
