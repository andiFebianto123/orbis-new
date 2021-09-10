<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\Appointment_history;
use App\Models\StatusHistory;
use App\Models\SpecialRolePersonel;
use App\Models\Relatedentity;
use App\Models\EducationBackground;
use App\Models\ChildNamePastors;
use App\Models\MinistryBackgroundPastor;
use App\Models\CareerBackgroundPastors;
use App\Models\StructureChurch;

class DetailPersonelApiController extends Controller
{

    public function biodata($id)
    {
        $personel = Personel::where('personels.id', $id)
                    ->leftJoin('rc_dpwlists', 'rc_dpwlists.id', 'personels.rc_dpw_id')
                    ->leftJoin('title_lists', 'title_lists.id', 'personels.title_id')
                    ->get(['personels.id as id', 'rc_dpwlists.rc_dpw_name', 'title_lists.short_desc as short_title', 'first_name', 'last_name', 'gender', 'profile_image', 'misc_image', 'date_of_birth', 'marital_status', 'spouse_name', 'spouse_date_of_birth', 'anniversary', 'notes', 'family_image'])
                    ->first();
        
        $status_history = StatusHistory::where('personel_id', $id)
                            ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                            ->get(['account_status.acc_status'])
                            ->first();

        $arr_personel = [];
        $arr_personel['status'] = (isset($status_history)) ? $status_history->acc_status : '-';
        $arr_personel['regional_council'] = $personel->rc_dpw_name;
        $arr_personel['short_title'] = $personel->short_title;
        $arr_personel['first_name'] = $personel->first_name;
        $arr_personel['last_name'] = $personel->last_name;
        $arr_personel['gender'] = $personel->gender;
        $arr_personel['profile_image'] = $personel->profile_image;
        $arr_personel['misc_image'] = $personel->misc_image;
        $arr_personel['date_of_birth'] = $personel->date_of_birth;
        $arr_personel['marital_status'] = $personel->marital_status;
        $arr_personel['spouse_name'] = $personel->spouse_name;
        $arr_personel['spouse_date_of_birth'] = $personel->spouse_date_of_birth;
        $arr_personel['anniversary'] = $personel->anniversary;
        $arr_personel['notes'] = $personel->notes;
        $arr_personel['family_image'] = $personel->family_image;
        
        $response = [
            'status' => true,
            'title' => 'Biodata',
            'data' => $arr_personel,
        ];

        return response()->json($response, 200); 
    }

    public function contactInformation($id)
    {
        $personel = Personel::where('personels.id', $id)
                    ->leftJoin('country_lists', 'country_lists.id', 'personels.country_id')
                    ->get(['street_address', 'city', 'province', 'country_lists.country_name', 'email', 'second_email', 'phone', 'fax', 'first_licensed_on', 'card', 'valid_card_start', 'valid_card_end', 'current_certificate_number', 'id_card'])
                    ->first();

        $arr_personel = [];
        $arr_personel['street_address'] = $personel->street_address;
        $arr_personel['city'] = $personel->city;
        $arr_personel['province'] = $personel->province;
        $arr_personel['postal_code'] = $personel->postal_code;
        $arr_personel['country_name'] = $personel->country_name;
        $arr_personel['email'] = $personel->email;
        $arr_personel['second_email'] = $personel->second_email;
        $arr_personel['phone'] = $personel->phone;
        $arr_personel['fax'] = $personel->fax;
        
        $response = [
            'status' => true,
            'title' => 'Contact Information',
            'data' => $arr_personel,
        ];

        return response()->json($response, 200); 
    }


    public function licensingInformation($id)
    {
        $personel = Personel::where('personels.id', $id)
                    ->leftJoin('country_lists', 'country_lists.id', 'personels.country_id')
                    ->get(['street_address', 'city', 'province', 'country_lists.country_name', 'email', 'second_email', 'phone', 'fax', 'first_licensed_on', 'card', 'valid_card_start', 'valid_card_end', 'current_certificate_number', 'id_card'])
                    ->first();

        $arr_personel = [];
        $arr_personel['first_licensed_on'] = $personel->first_licensed_on;
        $arr_personel['card'] = $personel->card;
        $arr_personel['valid_card_start'] = $personel->valid_card_start;
        $arr_personel['valid_card_end'] = $personel->valid_card_end;
        $arr_personel['current_certificate_number'] = $personel->current_certificate_number;
        $arr_personel['pastor_certificate'] = $personel->certificate;
        $arr_personel['pastor_id_card'] = $personel->id_card;
        
        $response = [
            'status' => true,
            'title' => 'Licensing Information',
            'data' => $arr_personel,
        ];

        return response()->json($response, 200); 
    }

    public function appointmentHistories($id)
    {
        $lists = Appointment_history::where('personel_id', $id)
                    ->get();
        
        $response = [
            'status' => true,
            'title' => 'Appointment Histories',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }


    public function specialRoles($id)
    {
        $lists = SpecialRolePersonel::where('personel_id', $id)
                ->leftJoin('special_roles', 'special_roles.id', 'special_role_personels.special_role_id')
                ->get(['special_role_personels.id as id', 'special_roles.special_role']);
        
        $response = [
            'status' => true,
            'title' => 'Special Roles',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function relatedEntities($id)
    {
        $lists = Relatedentity::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Related Entities',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function childNames($id)
    {
        $lists = ChildNamePastors::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Child Names',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function ministryBackgrounds($id)
    {
        $lists = MinistryBackgroundPastor::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Ministry Backgrounds',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function careerBackgrounds($id)
    {
        $lists = CareerBackgroundPastors::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Career Backgrounds',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function statusHistories($id)
    {
        $lists = StatusHistory::where('personel_id', $id)
                ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                ->get();

        $response = [
            'status' => true,
            'title' => 'Status Histories',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function churches($id){
        $churches = StructureChurch::where('personel_id', $id)
                    ->join('churches', 'churches.id', 'structure_churches.churches_id')
                    ->join('rc_dpwlists', 'rc_dpwlists.id', 'churches.rc_dpw_id')
                    ->get(['churches.*', 'rc_dpwlists.rc_dpw_name']);
        
        $response = [
            'status' => true,
            'title' => 'Church Information',
            'data' => $churches,
        ];
        
        return response()->json($response, 200); 
    }

    public function update(Request $request){
        $id = $request->id;

        $update_p = Personel::where('id', $id)->first();

        if (isset($request->rc_dpw_id)) {
            $update_p->rc_dpw_id = $request->rc_dpw_id;
        }
        if (isset($request->title_id)) {
            $update_p->title_id = $request->title_id;
        }
        if (isset($request->first_name)) {
            $update_p->first_name = $request->first_name;
        }
        if (isset($request->last_name)) {
            $update_p->last_name = $request->last_name;
        }
        if (isset($request->church_name)) {
            $update_p->church_name = $request->church_name;
        }
        if (isset($request->gender)) {
            $update_p->gender = $request->gender;
        }
        if (isset($request->date_of_birth)) {
            $update_p->date_of_birth = $request->date_of_birth;
        }
        if (isset($request->marital_status)) {
            $update_p->marital_status = $request->marital_status;
        }
        if (isset($request->spouse_name)) {
            $update_p->spouse_name = $request->spouse_name;
        }
        if (isset($request->spouse_date_of_birth)) {
            $update_p->spouse_date_of_birth = $request->spouse_date_of_birth;
        }
        if (isset($request->anniversary)) {
            $update_p->anniversary = $request->anniversary;
        }
        if (isset($request->street_address)) {
            $update_p->street_address = $request->street_address;
        }
        if (isset($request->city)) {
            $update_p->city = $request->city;
        }
        if (isset($request->province)) {
            $update_p->province = $request->province;
        }
        if (isset($request->postal_code)) {
            $update_p->postal_code = $request->postal_code;
        }
        if (isset($request->country_id)) {
            $update_p->country_id = $request->country_id;
        }
        if (isset($request->email)) {
            $update_p->email = $request->email;
        }
        if (isset($request->second_email)) {
            $update_p->second_email = $request->second_email;
        }
        if (isset($request->phone)) {
            $update_p->phone = $request->phone;
        }
        if (isset($request->fax)) {
            $update_p->fax = $request->fax;
        }
        if (isset($request->first_licensed_on)) {
            $update_p->first_licensed_on = $request->first_licensed_on;
        }
        if (isset($request->card)) {
            $update_p->card = $request->card;
        }
        if (isset($request->valid_card_start)) {
            $update_p->valid_card_start = $request->valid_card_start;
        }
        if (isset($request->valid_card_end)) {
            $update_p->valid_card_end = $request->valid_card_end;
        }

        $update_p->save();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

}
