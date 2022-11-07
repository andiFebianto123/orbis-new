<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChurchAnnualReportDetailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Church;
use App\Models\StatusHistoryChurch;
use App\Models\StatusHistory;
use App\Models\Personel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class QuickReportController extends Controller
{
    public function newchurch()
    {
        $new_church_tables = Church::whereYear('founded_on', Carbon::now()->year)
                        ->leftJoin('church_types','churches.church_type_id','church_types.id')
                        ->leftJoin('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                        ->leftJoin('country_lists','churches.country_id','country_lists.id')
                        ->select('rc_dpw_name','church_name','entities_type','lead_pastor_name','contact_person',
                        'church_address', 'office_address','city', 'province', 'postal_code','country_name',
                        'phone','fax','first_email','church_status','founded_on', 'service_time_church', 'notes')
                        ->get();

        $data['new_church_tables'] = $new_church_tables;

        return view('vendor.backpack.base.newchurchreport',$data);
    }

    public function newpastor()
    {
        $new_pastor_tables = Personel::whereYear('first_licensed_on', Carbon::now()->year)
                        ->leftJoin('country_lists','personels.country_id','country_lists.id')
                        ->leftJoin('account_status','personels.acc_status_id','account_status.id')
                        ->leftJoin('title_lists','personels.title_id','title_lists.id')
                        ->select('rc_dpw_name', 'short_desc', 'first_name','last_name', 'gender', 'church_name', 'street_address',
                        'city','province','postal_code','country_name','phone','fax','email','marital_status', 'date_of_birth',
                        'spouse_name','spouse_date_of_birth','anniversary','acc_status', 'first_licensed_on', 'card',
                        'valid_card_start', 'valid_card_end', 'current_certificate_number', 'notes')
                        ->get();

        $data['new_pastor_tables'] = $new_pastor_tables;

        return view('vendor.backpack.base.newpastorreport',$data);
    }

    public function inactivechurch()
    {
        $inactive_church_reports = StatusHistoryChurch::where('status', 'Non-active')
                    ->whereYear('date_status', Carbon::now()->year)
                    ->leftJoin('churches','status_history_churches.churches_id','churches.id')
                    ->leftJoin('church_types','churches.church_type_id','church_types.id')
                    ->leftJoin('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                    ->leftJoin('country_lists','churches.country_id','country_lists.id')
                    ->select('rc_dpw_name','church_name','entities_type','lead_pastor_name','contact_person',
                        'church_address', 'office_address','city', 'province', 'postal_code','country_name',
                        'phone','fax','first_email','founded_on', 'service_time_church', 'notes', 'status', 'date_status')
                    ->get();
        
        $data['inactive_church_reports'] = $inactive_church_reports;

        return view('vendor.backpack.base.inactivechurch',$data);
    }
    
    public function inactivepastor()
    {
        // $inactive_pastor_reports = StatusHistory::whereNotIn('status_histories_id', [1])
        //                 ->whereYear('date_status', Carbon::now()->year)
        //                 ->leftJoin('personels','status_histories.personel_id','personels.id')
        //                 ->leftJoin('rc_dpwlists','personels.rc_dpw_id','rc_dpwlists.id')
        //                 ->leftJoin('country_lists','personels.country_id','country_lists.id')
        //                 ->leftJoin('account_status','status_histories.status_histories_id','account_status.id')
        //                 ->leftJoin('title_lists','personels.title_id','title_lists.id')
        //                 ->select('rc_dpw_name', 'short_desc', 'first_name','last_name', 'gender', 'church_name', 'street_address',
        //                 'city','province','postal_code','country_name','phone','fax','email','marital_status', 'date_of_birth',
        //                 'spouse_name','spouse_date_of_birth','anniversary','acc_status', 'first_licensed_on', 'card',
        //                 'valid_card_start', 'valid_card_end', 'current_certificate_number', 'notes', 'date_status')
        //                 ->get();
    
        $inactive_pastor_reports = StatusHistory::whereNotIn('status', ['Active'])
                        ->whereYear('date_status', Carbon::now()->year)
                        ->leftJoin('personels','status_histories.personel_id','personels.id')
                        ->leftJoin('country_lists','personels.country_id','country_lists.id')
                        ->leftJoin('account_status','status_histories.status_histories_id','account_status.id')
                        ->leftJoin('title_lists','personels.title_id','title_lists.id')
                        ->select('rc_dpw_name', 'short_desc', 'first_name','last_name', 'gender', 'church_name', 'street_address',
                        'city','province','postal_code','country_name','phone','fax','email','marital_status', 'date_of_birth',
                        'spouse_name','spouse_date_of_birth','anniversary','status as acc_status', 'first_licensed_on', 'card',
                        'valid_card_start', 'valid_card_end', 'current_certificate_number', 'notes', 'date_status')
                        ->get();

        $data['inactive_pastor_reports'] = $inactive_pastor_reports;

        return view('vendor.backpack.base.inactivepastor',$data);
    }

    public function allchurch()
    {
        $all_church_tables = Church::leftJoin('church_types','churches.church_type_id','church_types.id')
                        ->leftJoin('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                        ->leftJoin('country_lists','churches.country_id','country_lists.id')
                        ->select('churches.id','rc_dpw_name','church_name','entities_type','lead_pastor_name','contact_person',
                        'church_address', 'office_address','city', 'province', 'postal_code','country_name',
                        'phone','fax','first_email','founded_on', 'service_time_church', 'notes')
                        ->get();

        $data['all_church_tables'] = $all_church_tables;

        return view('vendor.backpack.base.allchurchreport',$data);
    }

    public function allpastor()
    {
        $all_pastor_tables = Personel::leftJoin('country_lists','personels.country_id','country_lists.id')
                        ->leftJoin('title_lists','personels.title_id','title_lists.id')
                        ->select('personels.id','rc_dpw_name', 'short_desc', 'first_name','last_name', 'gender', 'church_name', 'street_address',
                        'city','province','postal_code','country_name','phone','fax','email','marital_status', 'date_of_birth',
                        'spouse_name','spouse_date_of_birth','anniversary', 'first_licensed_on', 'card',
                        'valid_card_start', 'valid_card_end', 'current_certificate_number', 'notes')
                        ->get();

        $data['all_pastor_tables'] = $all_pastor_tables;

        return view('vendor.backpack.base.allpastorreport',$data);
    }

}
