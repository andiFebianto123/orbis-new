<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ToolsUploadRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Church;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ChurchImport;
use App\Imports\PersonelImport;
use Excel;

class ToolsUploadController extends Controller
{
    public function index()
    {
        return view('vendor.backpack.base.tools');
    }

    public function importchurch()
    {
        return view('vendor.backpack.base.importchurch');
    }

    public function uploadchurch(Request $request)
    {
        Excel::import(new ChurchImport, request()->file('fileToUpload'));
        $status = 'Successfully Done';

        return back()->with(['status' => $status]);
    }

    public function importpersonel()
    {
        return view('vendor.backpack.base.importpersonel');
    }

    public function uploadpersonel(Request $request)
    {
        Excel::import(new PersonelImport, request()->file('fileToUpload'));
        $status = 'Successfully Done';
        
        return back()->with(['status' => $status]);
    }

}
