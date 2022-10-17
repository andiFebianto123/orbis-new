<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LeadershipSyncHelper;
use App\Http\Requests\StructureChurchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Personel;
use App\Models\Church;
use App\Models\StructureChurch;
use App\Helpers\HitCompare;
use App\Helpers\HitApi;
use Prologue\Alerts\Facades\Alert;

/**
 * Class StructureChurchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StructureChurchCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */

    public function setup()
    {
        CRUD::setModel(\App\Models\StructureChurch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/structurechurch');
        CRUD::setEntityNameStrings('Leadership Structure', 'Leadership Structure');
        // $this->crud->currentId = request()->churches_id;
        if (request()->churches_id) {
            $this->crud->currentId = request()->churches_id;
            $this->crud->redirectTo = backpack_url('church/'.$this->crud->currentId.'/show');
            $isChurchExists =  Church::where('id',$this->crud->currentId)->first();
            if($isChurchExists == null){
                abort(404);
            }
            $this->crud->saveOnly=true;
        }else if(request()->personel_id){
            $this->crud->currentId = request()->personel_id;
            $this->crud->redirectTo = backpack_url('personel/'.$this->crud->currentId.'/show');
            $isPersonelExists =  Personel::where('id',$this->crud->currentId)->first();
            if($isPersonelExists == null){
                abort(404);
            }
            $this->crud->saveOnly=true;
        }
        
    }

    public function index()
    {
        abort(404);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => "ID", // Table column heading
            'type' => 'number'
        ]);
        
        $this->crud->addColumn([
                'name'        => 'personel_id',
                'label'       => "Template",
                'type'        => 'select2_from_array',
                'options'   => $this->getPersonels(),
                'allows_null' => false,
            ]);   
            
            $this->crud->addColumn([
                'name' => 'churches_id', // The db column name
                'label' => "Church", // Table column heading
                'type' => 'relationship',
                'attribute' => 'church_name',
            ]);
        
        $this->crud->addColumn([
            'name' => 'ministry_role_church', // The db column name
            'label' => "Title", // Table column heading
            'type' => 'relationship',
            'attribute' => 'ministry_role',
        ]);

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StructureChurchRequest::class);

        if(request()->churches_id){
            $this->crud->addField([
                'label'     => "Pastor Name",
                'type'      => 'select2_from_array',
                'name'      => 'personel_id', // the column that contains the ID of that connected entity;
                'options'   => $this->getPersonels(),
                'allows_null' => false,
            ]);
            
            $this->crud->addField([
                'label'     => 'Church', // Table column heading
                'type'      => 'hidden',
                'name'      => 'churches_id', // the column that contains the ID of that connected entity;
                'default'   => request('churches_id')
            ]);
        }

        if(request()->personel_id){
            $this->crud->addField([
                'label'     => "Church Name",
                'type'      => 'select2_from_array',
                'name'      => 'church_id', // the column that contains the ID of that connected entity;
                'options'   => $this->getChurch(),
                'value'     => ($this->crud->getCurrentEntry())? StructureChurch::where('id', $this->crud->getCurrentEntry()->id)->first()->churches_id:0,
                'allows_null' => false,
            ]);
            
            $this->crud->addField([
                'label'     => 'Personel', // Table column heading
                'type'      => 'hidden',
                'name'      => 'personel_id', // the column that contains the ID of that connected entity;
                'default'   => request('personel_id')
            ]);
        }

        $this->crud->addField([
            'label'     => 'Role', // Table column heading
            'type'      => 'select2',
            'name'      => 'title_structure_id', // the column that contains the ID of that connected entity;
            'entity'    => 'ministry_role_church', // the method that defines the relationship in your Model
            'attribute' => 'ministry_role', // foreign key attribute that is shown to user
            'model'     => "App\Models\MinistryRole",
        ]);

       
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();


        $id = $request->personel_id;
        $entry = Personel::where('id', $id)->first();

        $church_name = false;
        $input_church_name = false;
        $trigger_matches_church = 0;
        $errors = [];
        $churches = StructureChurch::where('personel_id', $id)->get();
        $arr_unit = [];

        if($entry->church_name != '[]' || $entry->church_name !== '[]'){

            if(preg_match_all('/(\{[\:\"\_\,a-z0-9]+\})/', $entry->church_name, $matches)) {
                $church_name = $matches[1];
            }

            $input_church_name = '{"church_id":"'.$request->church_id.'","title_structure_id":"'.$request->title_structure_id.'"}';
        
            if(in_array($input_church_name, $church_name)){
                $errors['title_structure_id'] = ['The pastor with same church Name has already exists.'];
                $errors['church_id'] = ['The pastor with same church Name has already exists.'];
            }else{
                $trigger_matches_church = 1;
            }
        }else{
            $trigger_matches_church = 1;
        }

        if(count($errors) > 0){
            return redirect($this->crud->route . '/create?personel_id='. $id)
                ->withInput()->withErrors($errors);
        }


        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        Alert::success(trans('backpack::crud.insert_success'))->flash();

        $url_redirect = "";
        if (request()->churches_id) {
            $sc_by_church = StructureChurch::where('churches_id', request()->churches_id)->get();
            foreach ($sc_by_church as $key => $sc) {
                (new LeadershipSyncHelper())->sync($sc->personel_id);
            }
            $url_redirect = 'church/'.request()->churches_id.'/show';
        }else if (request()->personel_id) {
            (new LeadershipSyncHelper())->sync(request()->personel_id);

            $url_redirect = 'personel/'.request()->personel_id.'/show';
        }

        if($trigger_matches_church == 1){
            $send = new HitApi;
            $id = [$id];
            $module = 'user';
            $response = $send->action($id, 'update', $module)->json();
        }

        return redirect(backpack_url($url_redirect ));
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $id = $request->personel_id;
        $entry = Personel::where('id', $id)->first();
        $church_name = false;
        $input_church_name = false;
        $trigger_matches_church = 0;
        $errors = [];

        $check_kembar_data = StructureChurch::where('id', $request->id)->first();

        if($check_kembar_data != null){
            if(($check_kembar_data->title_structure_id == $request->title_structure_id) && 
            ($check_kembar_data->churches_id == $request->church_id)) {
                // welcome
                // nggak ada perubahan alias sama aja
                $trigger_matches_church = 0;
            }else{
                $check_ = StructureChurch::where('id', '<>', $request->id)
                ->where(function($query)use($request){
                    return $query->where('churches_id', $request->church_id)
                    ->where('title_structure_id', $request->title_structure_id);
                })->first();
                if($check_ != null){
                    $errors['title_structure_id'] = ['The pastor with same church Name has already exists.'];
                    $errors['church_id'] = ['The pastor with same church Name has already exists.'];
                }else{
                    $trigger_matches_church = 1;
                }
            }
        }else{
            $trigger_matches_church = 0;
        }
        

        if(count($errors) > 0){
            return redirect($this->crud->route . '/create?personel_id='. $id)
                ->withInput()->withErrors($errors);
        }

        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        Alert::success(trans('backpack::crud.update_success'))->flash();

        $url_redirect = "";
        if (request()->churches_id) {
            $sc_by_church = StructureChurch::where('churches_id', request()->churches_id)->get();
            foreach ($sc_by_church as $key => $sc) {
                (new LeadershipSyncHelper())->sync($sc->personel_id);
            }
            $url_redirect = 'church/'.request()->churches_id.'/show';
        }else if (request()->personel_id) {
            (new LeadershipSyncHelper())->sync(request()->personel_id);

            $url_redirect = 'personel/'.request()->personel_id.'/show';
        }

        if($trigger_matches_church == 1){
            $send = new HitApi;
            $id = [$id];
            $module = 'user';
            $response = $send->action($id, 'update', $module)->json();
        }
        
        return redirect(backpack_url($url_redirect));   
    }

    private function getPersonels(){
        // $personels = StructureChurch::join('personels', 'structur_churches.personel_id', 'personels.id')
        //             ->where('structur_churches.churches_id', request('churches_id'))
        //             ->get();
        $personels = Personel::get();
        $arr_personels = [];
        foreach ($personels as $key => $value) {
            $arr_personels[$value->id] = $value->first_name." ".$value->last_name;
        }

        return $arr_personels;
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $personel = request()->personel_id;

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $delete = $this->crud->delete($id);

        if($delete){
            $send = new HitApi;
            $id = [$personel];
            $module = 'user';
            $response = $send->action($id, 'update', $module)->json();
        }

        return $delete;

    }



    private function getChurch(){
        $churches = Church::get();
        $arr_churches = [];
        foreach ($churches as $key => $value) {
            $arr_churches[$value->id] = $value->church_name;
        }

        return $arr_churches;
    }
}
