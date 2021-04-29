<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ToolsUploadRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Church;
use App\Models\LogErrorExcel;
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
        $status = 'Successfully Done';
        
        try {
            $code = date("ymdhis");
            $file = request()->file('fileToUpload');
            $imports = Excel::import(new ChurchImport ($code, $file ), $file);
            
            $logerrors = LogErrorExcel::where('code',$code)->get();
    
            if (sizeof($logerrors) > 0) {
                $failures = [];
                foreach ($logerrors as $key => $logerror) {
                    $failures[] = [
                        'row'=> $logerror->row,
                        'errors' => json_decode($logerror->description)
                    ];
                }
                $data['failures']=$failures;
    
                return view('vendor.backpack.base.importchurch',$data);    
            }
            
            // if ($imports->onFailure()) {
            //     $data['failures']=$imports->onFailure();
            //     return view('vendor.backpack.base.importchurch',$data);
            // }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $data['failures']=$failures;
             return view('vendor.backpack.base.importchurch',$data);

            //  foreach ($failures as $failure) {
            //      $failure->row(); // row that went wrong
            //      $failure->attribute(); // either heading key (if using heading row concern) or column index
            //      $failure->errors(); // Actual error messages from Laravel validator
            //      $failure->values(); // The values of the row that has failed.
            //  }
        }

        return back()->with(['status' => $status]);
    }

    public function importpersonel()
    {
        return view('vendor.backpack.base.importpersonel');
    }

    public function uploadpersonel(Request $request)
    {
        
        $status = 'Successfully Done';
        
        try {
            $code = date("ymdhis");
            $file = request()->file('fileToUpload');
            $imports = Excel::import(new PersonelImport ($code, $file ), $file);
            
            $logerrors = LogErrorExcel::where('code',$code)->get();
    
            if (sizeof($logerrors) > 0) {
                $failures = [];
                foreach ($logerrors as $key => $logerror) {
                    $failures[] = [
                        'row'=> $logerror->row,
                        'errors' => json_decode($logerror->description)
                    ];
                }
                $data['failures']=$failures;
    
                return view('vendor.backpack.base.importpersonel',$data);    
            }
            
            // if ($imports->onFailure()) {
            //     $data['failures']=$imports->onFailure();
            //     return view('vendor.backpack.base.importpersonel',$data);
            // }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $data['failures']=$failures;
             return view('vendor.backpack.base.importpersonel',$data);

            //  foreach ($failures as $failure) {
            //      $failure->row(); // row that went wrong
            //      $failure->attribute(); // either heading key (if using heading row concern) or column index
            //      $failure->errors(); // Actual error messages from Laravel validator
            //      $failure->values(); // The values of the row that has failed.
            //  }
        }

        return back()->with(['status' => $status]);
    }

}
