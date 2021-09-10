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
use App\Models\Church;
use App\Models\CoordinatorChurch;
use App\Models\RelatedEntityChurch;
use App\Models\StatusHistoryChurch;
use App\Models\StructureChurch;

class DetailChurchApiController extends Controller
{

    public function information($id)
    {
        $church = Church::where('churches.id', $id)
                    ->leftJoin('rc_dpwlists', 'rc_dpwlists.id', 'churches.rc_dpw_id')
                    ->leftJoin('church_types', 'church_types.id', 'churches.church_type_id')
                    ->leftJoin('country_lists', 'country_lists.id', 'churches.country_id')
                    ->get(['churches.*', 'rc_dpwlists.rc_dpw_name', 'church_types.entities_type', 
                    'country_lists.country_name'])
                    ->first();

        $arr_church = [];
        $arr_church['status'] = (isset($church->active))?$church->active:"-";
        $arr_church['founded_on'] = (isset($church->founded_on))?$church->founded_on:"-";
        $arr_church['church_name'] = (isset($church->church_name))? $church->church_name:"-";
        $arr_church['contact_person'] = (isset($church->contact_person))? $church->contact_person:"-";
        $arr_church['building_name'] = (isset($church->building_name))? $church->building_name:"-";
        $arr_church['church_address'] = (isset($church->church_address))? $church->church_address:"-";
        $arr_church['office_address'] = (isset($church->office_address))? $church->office_address:"-";
        $arr_church['city'] = (isset($church->city))? $church->city:"-";
        $arr_church['province'] = (isset($church->province))? $church->province:"-";
        $arr_church['postal_code'] = (isset($church->postal_code))? $church->postal_code:"-";
        $arr_church['first_email'] = (isset($church->first_email))? $church->first_email:"-";
        $arr_church['second_email'] = (isset($church->second_email))? $church->second_email:"-";
        $arr_church['phone'] = (isset($church->phone))? $church->phone:"-";
        $arr_church['fax'] = (isset($church->fax))? $church->fax:"-";
        $arr_church['map_url'] = (isset($church->map_url))? $church->map_url:"-";
        $arr_church['website'] = (isset($church->website))? $church->website:"-";
        $arr_church['service_time_church'] = (isset($church->service_time_church))? $church->service_time_church:"-";
        $arr_church['certificate'] = (isset($church->certificate))? $church->certificate:"-";
        $arr_church['date_of_certificate'] = (isset($church->date_of_certificate))? $church->date_of_certificate:"-";
        $arr_church['notes'] = (isset($church->notes))? $church->notes:"-";
        $arr_church['rc_dpw_name'] = (isset($church->rc_dpw_name))? $church->rc_dpw_name:"-";
        $arr_church['church_type'] = (isset($church->entities_type ))?$church->entities_type:"-";
        $arr_church['country_name'] = (isset($church->country_name))? $church->country_name:"-";
        
        $response = [
            'status' => true,
            'title' => 'Information',
            'data' => $arr_church,
        ];

        return response()->json($response, 200); 
    }

    public function statusHistory($id)
    {
        $status_histories = StatusHistoryChurch::where('churches_id', $id)->get();
        $response = [
            'status' => true,
            'title' => 'Status History',
            'data' => $status_histories,
        ];

        return response()->json($response, 200); 
    }

    public function relatedEntity($id)
    {
        $related_entities = RelatedEntityChurch::where('churches_id', $id)->get();
        $response = [
            'status' => true,
            'title' => 'Related Entity',
            'data' => $related_entities,
        ];

        return response()->json($response, 200); 
    }

    public function coordinator($id)
    {
        $coordinators = CoordinatorChurch::where('churches_id', $id)->get();
        $response = [
            'status' => true,
            'title' => 'Coordinator',
            'data' => $coordinators,
        ];

        return response()->json($response, 200); 
    }

    public function leadership($id)
    {
        $leaderships = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                        ->join('title_lists', 'title_lists.id', 'structure_churches.title_structure_id')
                        ->where('structure_churches.churches_id', $id)
                        ->get(['structure_churches.churches_id as id', 'title_lists.long_desc', 'personels.first_name', 'personels.last_name']);
        $response = [
            'status' => true,
            'title' => 'Leadership',
            'data' => $leaderships,
        ];

        return response()->json($response, 200); 
    }

    

}
