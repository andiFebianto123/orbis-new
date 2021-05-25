<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RelatedEntityChurchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RelatedEntityChurchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RelatedEntityChurchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RelatedEntityChurch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/relatedentitychurch');
        CRUD::setEntityNameStrings('Related Entity Church', 'Related Entity Church');
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
            'name' => 'entity_church', // The db column name
            'label' => "Entity Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'type_entity', // The db column name
            'label' => "Type (Foundation, Bussiness Unit, Non Profit Organization, etc)", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'churches_id', // The db column name
            'label' => "Church", // Table column heading
            'type' => 'relationship',
            'attribute' => 'church_name',
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
        CRUD::setValidation(RelatedEntityChurchRequest::class);

        $this->crud->addField([
            'name'            => 'entity_church',
            'label'           => "Entity Name",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'type_entity',
            'label'           => "Type (Foundation, Bussiness Unit, Non Profit Organization, etc)",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'label'     => 'Church', // Table column heading
            'type'      => 'hidden',
            'name'      => 'churches_id', // the column that contains the ID of that connected entity;
            'default'   => request('churches_id')
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
