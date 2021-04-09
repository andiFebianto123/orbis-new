<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceTimeChurchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServiceTimeChurchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServiceTimeChurchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ServiceTimeChurch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/servicetimechurch');
        CRUD::setEntityNameStrings('Service Time', 'Service Time');
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
            'name' => 'service_type_id', // The db column name
            'label' => "Service", // Table column heading
            'type' => 'relationship',
            'attribute' => 'church_service',
        ]);

        $this->crud->addColumn([
            'name' => 'service_time', // The db column name
            'label' => "Time", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'service_room', // The db column name
            'label' => "Room", // Table column heading
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
        CRUD::setValidation(ServiceTimeChurchRequest::class);

        $this->crud->addField([
            'label'     => 'Service', // Table column heading
            'type'      => 'select2',
            'name'      => 'service_type_id', // the column that contains the ID of that connected entity;
            'entity'    => 'service_type_church', // the method that defines the relationship in your Model
            'attribute' => 'church_service', // foreign key attribute that is shown to user
            'model'     => "App\Models\ServiceType",
        ]);

        $this->crud->addField([
            'name'            => 'service_time',
            'label'           => "Time",
            'type'            => 'datetime_picker',
    
            // optional:
            'datetime_picker_options' => [
                'format' => 'DD/MM/YYYY HH:mm',
                'language' => 'en'
            ],
            'allows_null' => true,
            // 'default' => '2017-05-12 11:59:59',
        ]);

        $this->crud->addField([
            'name'            => 'service_room',
            'label'           => "Room",
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
