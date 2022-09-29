<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;
use App\Helpers\HitApi;
use App\Helpers\HitCompare;
/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('User', 'Users');
        if (backpack_user()->hasAnyRole(['Editor','Viewer']))
        {
            $this->crud->denyAccess('list');
        }
        dd(trans('validation.custom.model_has_relation'));
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // $this->crud->setColumns(['id','name', 'email', 'privilege', 'role', 'status_user']);

        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => 'No.',
            'orderable' => true,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name' => 'name', // The db column name
            'label' => "Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'email', // The db column name
            'label' => "Email", // Table column heading
            'type' => 'email'
        ]);

        $this->crud->addColumn([
            'name' => 'privilege', // The db column name
            'label' => "Privilege", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'status_user', // The db column name
            'label' => "Status User", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'role_id', // The db column name
            'label' => "Role", // Table column heading
            'type' => 'relationship',
            'attribute' => 'name'
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
        CRUD::setValidation(UserRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => "Name"
        ]);

        $this->crud->addField([
            'name' => 'email',
            'type' => 'text',
            'label' => "Email"
        ]);
        $this->crud->addField([
            'name' => 'privilege',
            'type' => 'select2_from_array',
            'label' => "Privilege",
            'options' => ['Power User' => "Power User", 'Management' => "Management",
            'Pastor/Church Level User' => "Pastor/Church Level User",
            'Administrator' => "Administrator"
            ],
        ]);

        $this->crud->addField([
            'name' => 'password', // The db column name
            'label' => "Password", // Table column heading
            'type' => 'password'
        ]);

        $this->crud->addField([
            'name' => 'status_user',
            'type' => 'select2_from_array',
            'label' => "Status User",
            'options' => ['Active' => "Active", 'Non Active' => "Non Active"],
            // 'allows_null' => false,
        ]);

        $this->crud->addField([
            'name'        => 'role_id', // the name of the db column
            'label'       => 'Role', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                1 => "Super Admin",
                2 => "Editor",
                3 => "Viewer"
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        $store = $this->getStore();

        if ($this->crud->getRequest()->role_id) {
            $role_id = $this->crud->getRequest()->role_id;
            $user_id = $this->crud->entry->id;
            
            //delete the old one
            \App\Models\ModelHasRole::where('model_id', $user_id)->delete();
            
            //create new one
            $model_has_role = new \App\Models\ModelHasRole();
            $model_has_role->role_id = $role_id;
            $model_has_role->model_type = 'App\Models\User';
            $model_has_role->model_id = $user_id;
            $model_has_role->save();

        }

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        return $store;
    }

    public function getStore(){
    
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        // hit api for update user
        $send = new HitApi;

        $id = [$item->getKey()];

        $module = 'user_admin';

        $response = $send->action($id, 'create', $module)->json();

        return $this->crud->performSaveAction($item->getKey());
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

    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        // $this->crud->removeField('password_confirmation');

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        if ($this->crud->getRequest()->role_id) {
            $role_id = $this->crud->getRequest()->role_id;
            $user_id = $this->crud->getRequest()->id;
            
            //delete the old one
            \App\Models\ModelHasRole::where('model_id', $user_id)->delete();
            
            //create new one
            $model_has_role = new \App\Models\ModelHasRole();
            $model_has_role->role_id = $role_id;
            $model_has_role->model_type = 'App\Models\User';
            $model_has_role->model_id = $user_id;
            $model_has_role->save();
        }

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        // return $this->traitUpdate();
        return $this->getUpdate();
    }

    public function getUpdate()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $id = $request->get($this->crud->model->getKeyName());

        $item_previous = $this->crud->getEntry($id); // adalah data sebelumnya
        
        $hitCompare = new HitCompare;
        $hitCompare->addFieldCompare(
            [
                'name' => 'name',
                'email' => 'email',
                'privilege' => 'privilege',
                'status_user' => 'status_user',
                'role_id' => 'role_id',
            ], 
        $request->all());

        $com = $hitCompare->compareData($item_previous->toArray());

        if($com){
            $send = new HitApi;
            $id = [$com];
            $module = 'user_admin';
            $response = $send->action($id, 'update', $module)->json();
        }

        // hit api for update user
        // $send = new HitApi;

        // $id = [$item->getKey()];

        // $module = 'user';
        // if($item->role->name === 'Super Admin'){
        //     $module = "user_admin";
        // }

        // $response = $send->action($id, 'update', $module)->json();


        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();


        return $this->crud->performSaveAction($item->getKey());
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $delete = $this->crud->delete($id);

        // hit api for update user
        $send = new HitApi;
        $ids = [$id];
        $module = 'user_admin';
        $response = $send->action($ids, 'delete', $module)->json();

        return $delete;
    }

}
