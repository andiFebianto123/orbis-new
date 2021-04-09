<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SpecialRolePersonelRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SpecialRolePersonelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SpecialRolePersonelCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SpecialRolePersonel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/specialrolepersonel');
        CRUD::setEntityNameStrings('Special Role', 'Special Role');
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
            'attribute' => 'special_role_name',
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
            'type'      => 'select2',
            'name'      => 'special_role_id', // the column that contains the ID of that connected entity;
            'entity'    => 'special_role_personel', // the method that defines the relationship in your Model
            'attribute' => 'special_role_name', // foreign key attribute that is shown to user
            'model'     => "App\Models\SpecialRole",
        ]);

        $this->crud->addField([
            'label'     => 'Personel', // Table column heading
            'type'      => 'hidden',
            'name'      => 'personel_id', // the column that contains the ID of that connected entity;
            'default'   => request('personel_id')
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
}
