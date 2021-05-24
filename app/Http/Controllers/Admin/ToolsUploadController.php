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
use Maatwebsite\Excel\HeadingRowImport;
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
        $status_error = 'Invalid File';

        $request->validate(['fileToUpload'=>'required|file|mimes:xls,xlsx']);
        $headings = (new HeadingRowImport)->toArray($request->fileToUpload);

        $currentheading = $headings[0] ?? [];
        $currentheading = $currentheading[0] ?? [];
        $correctheading = [ 0 => "rc_dpw",
        1 => "church_name",
        2 => "church_type",
        3 => "lead_pastor_name",
        4 => "contact_person",
        5 => "church_address",
        6 => "office_address",
        7 => "city",
        8 => "province",
        9 => "postal_code",
        10 => "country",
        11 => "phone",
        12 => "fax",
        13 => "first_email",
        14 => "church_status",
        15 => "founded_on",
        16 => "service_time_church",
        17 => "notes"];

        foreach($currentheading as $current){
            $index = array_search(strtolower($current), $correctheading);
            if ($index !== false) {
                unset($correctheading[$index]);
            }
        }

        if(count($correctheading)!=0){
            return redirect ( backpack_url ('import-church'))->with(['status_error' => $status_error]);
        }

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
        $status_error = 'Invalid File';
        
        $request->validate(['fileToUpload'=>'required|file|mimes:xls,xlsx']);
        $headings = (new HeadingRowImport)->toArray($request->fileToUpload);

        $currentheading = $headings[0] ?? [];
        $currentheading = $currentheading[0] ?? [];
        $correctheading = [0 => "dpw",
        1 => "title",
        2 => "first_name",
        3 => "last_name",
        4 => "gender",
        5 => "church_name",
        6 => "address",
        7 => "city",
        8 => "province",
        9 => "postal_code",
        10 => "country",
        11 => "phone",
        12 => "fax",
        13 => "email",
        14 => "marital_status",
        15 => "date_of_birth",
        16 => "spouse_name",
        17 => "spouse_date_of_birth",
        18 => "anniversary",
        19 => "acc_status",
        20 => "first_licensed_on",
        21 => "card",
        22 => "valid_card_start",
        23 => "valid_card_end",
        24 => "current_certificate_number",
        25 => "notes"];

        foreach($currentheading as $current){
            $index = array_search(strtolower($current), $correctheading);
            if ($index !== false) {
                unset($correctheading[$index]);
            }
        }

        if(count($correctheading)!=0){
            return redirect ( backpack_url ('import-personel'))->with(['status_error' => $status_error]);
        }

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

    public function importcountry()
    {
        return view('vendor.backpack.base.importcountry');
    }

    public function importrcdpw()
    {
        return view('vendor.backpack.base.importrcdpw');
    }

}
