<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MinistryBackgroundPastorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MinistryBackgroundPastorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MinistryBackgroundPastorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MinistryBackgroundPastor::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ministrybackgroundpastor');
        CRUD::setEntityNameStrings('Ministry Background', 'Ministry Background');
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
            'name' => 'ministry_title', // The db column name
            'label' => "Title", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'ministry_description', // The db column name
            'label' => "Description", // Table column heading
            'type' => 'text'
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
        CRUD::setValidation(MinistryBackgroundPastorRequest::class);

        $this->crud->addField([
            'name'            => 'ministry_title',
            'label'           => "Title",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'ministry_description',
            'label'           => "Description",
            'type'            => 'text',
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
