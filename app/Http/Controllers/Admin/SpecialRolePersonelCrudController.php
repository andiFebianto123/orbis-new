<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SpecialRolePersonelRequest;
use App\Models\Personel;
use App\Models\RcDpwList;
use App\Models\SpecialRolePersonel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;
use App\Helpers\HitApi;


/**
 * Class SpecialRolePersonelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SpecialRolePersonelCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SpecialRolePersonel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/specialrolepersonel');
        CRUD::setEntityNameStrings('Special Role', 'Special Role');
        $this->crud->currentId = request()->personel_id;
        $this->crud->redirectTo = backpack_url('personel/'.$this->crud->currentId.'/show');
        $isPersonelExists =  Personel::where('id',$this->crud->currentId)->first();
        if($isPersonelExists == null){
            abort(404);
        }
        $this->crud->saveOnly=true;
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
            'name' => 'special_role_personel', // The db column name
            'label' => "Special Role Personel", // Table column heading
            'type' => 'relationship',
            'attribute' => 'special_role',
        ]);

        $this->crud->addColumn([
            'name' => 'personel', // The db column name
            'label' => "Personel", // Table column heading
            'type' => 'relationship',
            'attribute' => 'first_name',
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
        CRUD::setValidation(SpecialRolePersonelRequest::class);

        $this->crud->addField([
            'label'     => 'Special Role Personel', // Table column heading
            'type'      => 'select2_rel_checklist',
            'name'      => 'special_role_id', // the column that contains the ID of that connected entity;
            'entity'    => 'special_role_personel', // the method that defines the relationship in your Model
            'attribute' => 'special_role', // foreign key attribute that is shown to user
            'model'     => "App\Models\SpecialRole",
        ]);

        $this->crud->addField([
            'label'     => 'Personel', // Table column heading
            'type'      => 'hidden',
            'name'      => 'personel_id', // the column that contains the ID of that connected entity;
            'default'   => request('personel_id')
        ]);

        $this->crud->addField([
            'name'        => 'rc_dpw',
            'label'       => "RC / DPW List",
            'type'        => 'checklist_table_ajax',
            'ajax_url'    => url('admin/ajax-rcdpw'),
            'table'       =>  ['table_header' => $this->rcdpwList()['header']]
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
        $rcDpw = null;
        $trigger_matches_church = 0;
        if ($request->special_role_id == 3) {
            $rcDpw = json_encode($request->rc_dpw);
        }
        $change = SpecialRolePersonel::where('special_role_id', $request->special_role_id)
                    ->where('personel_id',  $request->personel_id)
                    ->first();
        $errors = [];

        if($change){
            $errors['special_role_id'] = ['The pastor with same Special role has already exists.'];
        }

        $rcdpw_list = false;
        $input_rcdpw_list = false;

        if($change){
            if(($change->special_role_id == 3) && ($request->special_role_id == 3)){

                if(preg_match_all('/(\"[\"0-9]+\")/', $change->rc_dpw, $matches)) {
                    $rcdpw_list = $matches[1];
                }

                if(preg_match_all('/(\"[\"0-9]+\")/', $rcDpw, $matches)) {
                    $input_rcdpw_list = $matches[1];
                }

                foreach($input_rcdpw_list as $list){
                    if(!in_array($list, $rcdpw_list)){
                        $trigger_matches_church = 1;
                    }
                }

            }
        }else{
            $trigger_matches_church = 1;
        }

        if(count($errors)){
            return redirect($this->crud->route . '/create?personel_id=' .$request->personel_id)
                ->withInput()->withErrors($errors);
        }

        if (!isset($change)) {
            $insert = new SpecialRolePersonel();
            $insert->special_role_id = $request->special_role_id;
            $insert->rc_dpw = $rcDpw;
            $insert->personel_id = $request->personel_id;
            $insert->save();
        }

        if($trigger_matches_church == 1){
            $send = new HitApi;
            $id = [$request->personel_id];
            $module = 'user';
            $response = $send->action($id, 'update', $module)->json();
        }

        Alert::success(trans('backpack::crud.insert_success'))->flash();

        return redirect(backpack_url('personel/'.$request->personel_id.'/show'));
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $rcDpw = null;
        if ($request->special_role_id == 3) {
            $rcDpw = json_encode($request->rc_dpw);
        }

        // this data
        $findCombination = SpecialRolePersonel::where('id', $request->id)
                        ->where('personel_id', $request->personel_id)
                        ->first();

        $errors = [];

        if($findCombination->special_role_id != $request->special_role_id){
            $findDouble = SpecialRolePersonel::where('special_role_id', $request->special_role_id)
            ->where('personel_id', $request->personel_id)
            ->first();
            if($findDouble){
                 $errors['special_role_id'] = ['The pastor with same Special role has already exists.'];
            }
        }

        if(count($errors)){
            return redirect($this->crud->route .'/'. $request->id . '/edit?personel_id='.$request->personel_id)
                ->withInput()->withErrors($errors);
        }

        $rcdpw_list = [];
        $input_rcdpw_list = [];
        $trigger_matches_church = 0;


        if( ($request->special_role_id == 3) && ($findCombination->special_role_id == 3) && ($rcDpw != 'null')){

            if(preg_match_all('/(\"[\"0-9]+\")/', $findCombination->rc_dpw, $matches)) {
                $rcdpw_list = $matches[1];
            }else{
                $trigger_matches_church = 1;
            }

            if(preg_match_all('/(\"[\"0-9]+\")/', $rcDpw, $matches)) {
                $input_rcdpw_list = $matches[1];
            }

            foreach($input_rcdpw_list as $list){
                if(!in_array($list, $rcdpw_list)){
                    $trigger_matches_church = 1;
                }
            }
        }else if($request->special_role_id != $findCombination->special_role_id){
            $trigger_matches_church = 1;
        }


        // if (isset($findCombination)) {
        //     // SpecialRolePersonel::where('id', $findCombination->id)->delete();
        // }

        $change = SpecialRolePersonel::where('id', $request->id)->first();
        $change->special_role_id = $request->special_role_id;
        if($rcDpw != 'null'){
            $change->rc_dpw = $rcDpw;
        }
        $change->personel_id = $request->personel_id;
        $change->save();

        if($trigger_matches_church == 1){
            $send = new HitApi;
            $id = [$request->personel_id];
            $module = 'user';
            $response = $send->action($id, 'update', $module)->json();
        }

        // show a success message
        Alert::success(trans('backpack::crud.update_success'))->flash();

        return redirect(backpack_url('personel/'.$request->personel_id.'/show'));    
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


    private function rcdpwList(){
        $tableHeader = [];
        $tableHeader[] = 'Regional Council / DPW Name';
        
        $table['header'] = $tableHeader;
        $table['body'] = [];

        return $table;
    }
}
