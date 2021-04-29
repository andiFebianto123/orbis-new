<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChurchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ChurchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChurchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Church::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/church');
        CRUD::setEntityNameStrings('Church / Office', 'Church & Office List');
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
            'name' => 'church_status', // The db column name
            'label' => "Church Status", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'church_id', // The db column name
            'label' => "Church ID", // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'rc_dpw', // The db column name
            'label' => "RC / DPW", // Table column heading
            'type' => 'relationship',
            'attribute' => 'rc_dpw_name',
        ]);

        $this->crud->addColumn([
            'name' => 'church_type', // The db column name
            'label' => "Type", // Table column heading
            'type' => 'relationship',
            'attribute' => 'entities_type',
        ]);

        $this->crud->addColumn([
            'name' => 'church_name', // The db column name
            'label' => "Church Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'lead_pastor_name', // The db column name
            'label' => "Lead Pastor", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'contact_person', // The db column name
            'label' => "Contact Person", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'first_email', // The db column name
            'label' => "Email", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'phone', // The db column name
            'label' => "Phone", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'fax', // The db column name
            'label' => "Fax", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'church_address', // The db column name
            'label' => "Church Address", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'office_address', // The db column name
            'label' => "Office Address", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'city', // The db column name
            'label' => "City", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'province', // The db column name
            'label' => "Province", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'postal_code', // The db column name
            'label' => "Postal Code", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'country', // The db column name
            'label' => "Country", // Table column heading
            'type' => 'relationship',
            'attribute' => 'country_name',
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
        CRUD::setValidation(ChurchRequest::class);

        $this->crud->addField([
            'name'            => 'church_status',
            'label'           => "Status",
            'type'            => 'select2_from_array',
            'options'         => ['Active' => "Active", 'Non Active' => "Non Active"],
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'  => 'founded_on',
            'type'  => 'date_picker',
            'label' => 'Founded On',
            'tab'   => 'Church / Office Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'church_id',
            'type'  => 'number',
            'label' => 'Church ID',
            'tab'   => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'label'     => 'Type', // Table column heading
            'type'      => 'select2',
            'name'      => 'church_type_id', // the column that contains the ID of that connected entity;
            'entity'    => 'church_type', // the method that defines the relationship in your Model
            'attribute' => 'entities_type', // foreign key attribute that is shown to user
            'model'     => "App\Models\ChurchEntityType",
            'tab'       => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'label'     => 'RC/DPW', // Table column heading
            'type'      => 'select2',
            'name'      => 'rc_dpw_id', // the column that contains the ID of that connected entity;
            'entity'    => 'rc_dpw', // the method that defines the relationship in your Model
            'attribute' => 'rc_dpw_name', // foreign key attribute that is shown to user
            'model'     => "App\Models\RcDpwList",
            'tab'       => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'church_name',
            'label'           => "Church Name",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'lead-pastor_name',
            'label'           => "Lead Pastor",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'contact_person',
            'label'           => "Contact Person",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'building_name',
            'label'           => "Building Name",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'church_address',
            'label'           => "Church Address",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'office_address',
            'label'           => "Office Address",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'city',
            'label'           => "City",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'province',
            'label'           => "Province",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'postal_code',
            'label'           => "Postal Code",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'label'     => 'Country', // Table column heading
            'type'      => 'select2',
            'name'      => 'country_id', // the column that contains the ID of that connected entity;
            'entity'    => 'country', // the method that defines the relationship in your Model
            'attribute' => 'country_name', // foreign key attribute that is shown to user
            'model'     => "App\Models\CountryList",
            'tab'       => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'first_email',
            'label'           => "Email 1",
            'type'            => 'email',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'second_email',
            'label'           => "Email 2",
            'type'            => 'email',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'phone',
            'label'           => "Phone",
            'type'            => 'number',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'fax',
            'label'           => "Fax",
            'type'            => 'number',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'website',
            'label'           => "Website",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'map_url',
            'label'           => "Map Url",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
        ]);

        $this->crud->addField([
            'name'            => 'service_time_church',
            'label'           => "Service Time",
            'type'            => 'text',
            'tab'             => 'Church / Office Information',
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

    public function show()
    {
        $this->crud->getCurrentEntry();
        $data['crud'] = $this->crud;
        return view('vendor.backpack.crud.showchurch',$data);
    }
}
