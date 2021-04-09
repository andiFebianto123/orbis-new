<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StatusHistoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StatusHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StatusHistoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StatusHistory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/statushistory');
        CRUD::setEntityNameStrings('Status History', 'Status Histories');
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
            'name' => 'status', // The db column name
            'label' => "Status", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'reason', // The db column name
            'label' => "Reason", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'date_status', // The db column name
            'label' => "Date", // Table column heading
            'type' => 'date'
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
        CRUD::setValidation(StatusHistoryRequest::class);

        $this->crud->addField([
            'name'            => 'status',
            'label'           => "Status",
            'options'         => ['Active' => "Active", 'Non Active' => "Non Active"],
            'type'            => 'select2_from_array',
        ]);

        $this->crud->addField([
            'name'            => 'reason',
            'label'           => "Reason",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'  => 'date_status',
            'type'  => 'date_picker',
            'label' => 'Date Status',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
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
