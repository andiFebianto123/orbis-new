<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PastorReportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Personel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PastorAnnualReportController extends Controller
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PastorReport::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/pastorreport');
        CRUD::setEntityNameStrings('Pastor Report', 'Pastor Report');
    }

    public function index()
    {
        $pastor_report_tables = Personel::select(DB::raw("(COUNT(first_lisenced_on)) as total"),DB::raw("YEAR(first_lisenced_on) as year"))
                        ->groupBy('year')
                        ->get();

        $data['pastor_report_tables'] = $pastor_report_tables;

        return view('vendor.backpack.base.pastorreport',$data);
    }

    public function detail($year)
    {
        $pastor_report_detail_tables = Personel::whereYear('first_lisenced_on', $year)
                        ->join('account_status','personels.acc_status_id','account_status.id')
                        ->join('rc_dpwlists','personels.rc_dpw_id','rc_dpwlists.id')
                        ->join('country_lists','personels.country_id','country_lists.id')
                        ->select('first_name','rc_dpw_name','country_name','acc_status','first_lisenced_on')
                        ->get();

        $data['year'] = $year;
        $data['pastor_report_detail_tables'] = $pastor_report_detail_tables;

        return view('vendor.backpack.base.pastorreportdetail',$data);
    }

    public function reportdesigner()
    {
        $pastor_report_designs = Personel::leftJoin('rc_dpwlists','personels.rc_dpw_id','rc_dpwlists.id')
                        ->leftJoin('account_status','personels.acc_status_id','account_status.id')
                        ->leftJoin('title_lists','personels.title_id','title_lists.id')
                        ->select('rc_dpw_name','short_desc','first_name','street_address','phone','fax','email','card',
                        'date_of_birth','spouse_name','spouse_date_of_birth','anniversary','acc_status')
                        ->get();

        $data['pastor_report_designs'] = $pastor_report_designs;

        return view('vendor.backpack.base.pastorreportdesigner', $data);
    }
    
}
