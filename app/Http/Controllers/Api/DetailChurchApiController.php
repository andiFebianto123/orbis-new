<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LogHubApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\CoordinatorChurch;
use App\Models\RelatedEntityChurch;
use App\Models\StatusHistoryChurch;
use App\Models\StructureChurch;
use Illuminate\Support\Facades\DB;

class DetailChurchApiController extends Controller
{
    public function list(){

        $filters = [];
        if (request('city')) {
            $filters[] = ['city', 'LIKE', '%'.request('city').'%'];
        }
        
        $churches = Church::where($filters)
                    ->leftJoin('rc_dpwlists', 'rc_dpwlists.id', 'churches.rc_dpw_id')
                    ->leftJoin('church_types', 'church_types.id', 'churches.church_type_id')
                    ->leftJoin('country_lists', 'country_lists.id', 'churches.country_id')
                    ->with('last_status')
                    ->get(['churches.id', 'churches.church_address', 'church_name', 'city', 'phone', 'map_url', 'website', 'service_time_church', 'first_email', 'churches.created_at']);
        $arr_res = [];
        foreach ($churches as $key => $church) {
            $status_church = ($church->last_status) ? $church->last_status->status:'-';
            if ($status_church == 'Active') {
                $arr_personel = [];
                if(StructureChurch::where('churches_id', $church->id)->exists()){
                    $leaderships = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                            ->join('title_lists', 'title_lists.id', 'structure_churches.title_structure_id')
                            ->where('structure_churches.churches_id', $church->id)
                            ->get(['structure_churches.churches_id as id', 'title_lists.long_desc', 'personels.first_name', 'personels.last_name', 'structure_churches.created_at']);
                    $arr_personel = $leaderships;
                }
                $arr_res[] = [
                    'id' => $church->id,
                    'church_address' => $church->church_address,
                    'church_name' => $church->church_name,
                    'city' => $church->city,
                    'phone' => $church->phone,
                    'status_church' => $status_church,
                    'map_url' => $church->map_url,
                    'website' => $church->website,
                    'service_time_church' => $church->service_time_church,
                    'first_email' => $church->first_email,
                    'personels' => $arr_personel,
                ];
            }
        }

        return $arr_res;
    }


    public function information($id)
    {   
        $church = Church::where('churches.id', $id)
                    ->leftJoin('rc_dpwlists', 'rc_dpwlists.id', 'churches.rc_dpw_id')
                    ->leftJoin('church_types', 'church_types.id', 'churches.church_type_id')
                    ->leftJoin('country_lists', 'country_lists.id', 'churches.country_id')
                    ->get(['churches.*', 'rc_dpwlists.rc_dpw_name', 'church_types.entities_type', 
                    'country_lists.country_name'])
                    ->first();

        $arr_church = null;
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
        
        if(!StructureChurch::where('churches_id', $id)->where('personel_id', request('personel_id'))->exists()){
            $arr_church = null;
        }

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
        if(!StructureChurch::where('churches_id', $id)->where('personel_id', request('personel_id'))->exists()){
            $status_histories = [];
        }
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
        if(!StructureChurch::where('churches_id', $id)->where('personel_id', request('personel_id'))->exists()){
            $related_entities = [];
        }
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
        if(!StructureChurch::where('churches_id', $id)->where('personel_id', request('personel_id'))->exists()){
            $coordinators = [];
        }
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
                        ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                        ->join('title_lists', 'title_lists.id', 'personels.title_id')
                        ->where('structure_churches.churches_id', $id)
                        ->get(['structure_churches.id as id', 'ministry_roles.ministry_role as ministry_role', 
                        'title_lists.short_desc', 'title_lists.long_desc','personels.id as personel_id','personels.first_name', 'personels.last_name']);
        
        if(!StructureChurch::where('churches_id', $id)->where('personel_id', request('personel_id'))->exists()){
            $leaderships = [];
        }
        $leaderships_exist = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                        ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                        ->where('structure_churches.personel_id', request('personel_id'))
                        ->where('structure_churches.churches_id', $id)
                        ->where(function ($query) {
                            $query->where('ministry_roles.ministry_role', 'Lead Pastor')
                                  ->orWhere('ministry_roles.ministry_role', 'Senior Pastor');
                        })
                        ->exists();
        $can_crud = $leaderships_exist;
        
        $response = [
            'status' => true,
            'title' => 'Leadership',
            'can_crud' => $can_crud,
            'data' => $leaderships,
        ];

        return response()->json($response, 200); 
    }


    public function update(Request $request){
        $id = $request->id;
        $personel_id = $request->personel_id;

        $message_log = "Update ";

        $update_p = Church::where('id', $id)->first();

        if (isset($request->founded_on)) {
            $message_log .= "Founded On from ".$update_p->founded_on." to ".$request->founded_on;
            $update_p->founded_on = $request->founded_on;
        }
        if (isset($request->church_type_id)) {
            $message_log .= "Church Type ".$update_p->church_type_id." to ".$request->church_type_id;
            $update_p->church_type_id = $request->church_type_id;
        }
        if (isset($request->church_local_id)) {
            $message_log .= "Church Local ".$update_p->church_local_id." to ".$request->church_local_id;
            $update_p->church_local_id = $request->church_local_id;
        }
        if (isset($request->rc_dpw_id)) {
            $message_log .= "RC/DPW ".$update_p->rc_dpw_id." to ".$request->rc_dpw_id;
            $update_p->rc_dpw_id = $request->rc_dpw_id;
        }
        if (isset($request->church_name)) {
            $message_log .= "Church Name ".$update_p->church_name." to ".$request->church_name;
            $update_p->church_name = $request->church_name;
        }
        if (isset($request->contact_person)) {
            $message_log .= "Contact Person ".$update_p->contact_person." to ".$request->contact_person;
            $update_p->contact_person = $request->contact_person;
        }
        if (isset($request->building_name)) {
            $message_log .= "Building Name ".$update_p->building_name." to ".$request->building_name;
            $update_p->building_name = $request->building_name;
        }
        if (isset($request->church_address)) {
            $message_log .= "Church Address ".$update_p->church_address." to ".$request->church_address;
            $update_p->church_address = $request->church_address;
        }
        if (isset($request->office_address)) {
            $message_log .= "Office Address ".$update_p->office_address." to ".$request->office_address;
            $update_p->office_address = $request->office_address;
        }
        if (isset($request->city)) {
            $message_log .= "City ".$update_p->city." to ".$request->city;
            $update_p->city = $request->city;
        }
        if (isset($request->province)) {
            $message_log .= "Province ".$update_p->province." to ".$request->province;
            $update_p->province = $request->province;
        }
        if (isset($request->postal_code)) {
            $message_log .= "Postcode ".$update_p->postal_code." to ".$request->postal_code;
            $update_p->postal_code = $request->postal_code;
        }
        if (isset($request->country_id)) {
            $message_log .= "Country ".$update_p->country_id." to ".$request->country_id;
            $update_p->country_id = $request->country_id;
        }
        if (isset($request->first_email)) {
            $message_log .= "First Email ".$update_p->first_email." to ".$request->first_email;
            $update_p->first_email = $request->first_email;
        }
        if (isset($request->second_email)) {
            $message_log .= "Second Email ".$update_p->second_email." to ".$request->second_email;
            $update_p->second_email = $request->second_email;
        }
        if (isset($request->phone)) {
            $message_log .= "Phone ".$update_p->phone." to ".$request->phone;
            $update_p->phone = $request->phone;
        }
        if (isset($request->fax)) {
            $message_log .= "Fax ".$update_p->fax." to ".$request->fax;
            $update_p->fax = $request->fax;
        }
        if (isset($request->website)) {
            $message_log .= "Website ".$update_p->website." to ".$request->website;
            $update_p->website = $request->website;
        }
        if (isset($request->map_url)) {
            $message_log .= "Map Url ".$update_p->map_url." to ".$request->map_url;
            $update_p->map_url = $request->map_url;
        }
        if (isset($request->service_time_church)) {
            $message_log .= "Service Time Church ".$update_p->service_time_church." to ".$request->service_time_church;
            $update_p->service_time_church = $request->service_time_church;
        }
        if (isset($request->certificate)) {
            $message_log .= "Certificate";
            $update_p->certificate = $request->certificate;
        }
        if (isset($request->date_of_certificate)) {
            $message_log .= "Date of Certificate ".$update_p->date_of_certificate." to ".$request->date_of_certificate;
            $update_p->date_of_certificate = $request->date_of_certificate;
        }
        if (isset($request->notes)) {
            $message_log .= "Notes ".$update_p->notes." to ".$request->notes;
            $update_p->notes = $request->notes;
        }

        $update_p->save();

        (new LogHubApi())->save($personel_id, $message_log, 'church');

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

    public function cekLog(){
        (new LogHubApi())->save(1, "Cek", 'church');
    }

}
