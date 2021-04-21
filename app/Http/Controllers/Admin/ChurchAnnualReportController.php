<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChurchAnnualReportDetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Church;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChurchAnnualReportController extends Controller
{
    public function index()
    {
        $church_report_tables = Church::select(DB::raw("(COUNT(founded_on)) as total"),DB::raw("YEAR(founded_on) as year"))
                            ->groupBy('year')
                            ->get();
                 
        $data['church_report_tables'] = $church_report_tables;

        return view('vendor.backpack.base.churchreport',$data);
    }

    public function detail($year)
    {
        $church_report_detail_tables = Church::whereYear('founded_on', $year)
                        ->join('church_types','churches.church_type_id','church_types.id')
                        ->join('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                        ->join('country_lists','churches.country_id','country_lists.id')
                        ->select('entities_type','rc_dpw_name','country_name','church_name','founded_on')
                        ->get();

        $data['year'] = $year;
        $data['church_report_detail_tables'] = $church_report_detail_tables;

        return view('vendor.backpack.base.churchreportdetail',$data);
    }

    public function reportdesigner()
    {
        $church_report_designs = Church::leftJoin('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                        ->leftJoin('service_time_churches','service_time_churches.churches_id','churches.id')
                        ->leftJoin('church_types','churches.church_type_id','church_types.id')
                        ->leftJoin('country_lists','churches.country_id','country_lists.id')
                        ->select('rc_dpw_name', 'church_name','entities_type','contact_person','church_address','office_address',
                        'country_name','phone','fax','first_email','church_status','founded_on','service_time')
                        ->get();

        $data['church_report_designs'] = $church_report_designs;

        return view('vendor.backpack.base.churchreportdesigner', $data);
    }

}
