<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
            // $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        $store = $this->traitStore();

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
            // $request->request->set('password', Hash::make($request->input('password')));
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

        return $this->traitUpdate();
    }

}
