<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonelRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

/**
 * Class PersonelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonelCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Personel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/personel');
        CRUD::setEntityNameStrings('Pastor', 'Pastors');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {        
        // $this->crud->addColumn([
        //     'name' => 'id', // The db column name
        //     'label' => "ID", // Table column heading
        //     'type' => 'number'
        // ]);

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => 'No.',
            'orderable' => true,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name' => 'title', // The db column name
            'label' => "Title", // Table column heading
            'type' => 'relationship',
            'attribute' => 'short_desc',
        ]);

        $this->crud->addColumn([
            'name' => 'first_name', // The db column name
            'label' => "First Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'last_name', // The db column name
            'label' => "Last Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'accountstatus', // The db column name
            'label' => "Status", // Table column heading
            'type' => 'relationship',
            'attribute' => 'acc_status',
        ]);

        $this->crud->addColumn([
            'name' => 'rc_dpw', // The db column name
            'label' => "RC / DPW", // Table column heading
            'type' => 'relationship',
            'attribute' => 'rc_dpw_name',
        ]);

        $this->crud->addColumn([
            'name' => 'church_name', // The db column name
            'label' => "Church Name", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'email', // The db column name
            'label' => "Email 1", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'Second_email', // The db column name
            'label' => "Email 2", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'phone', // The db column name
            'label' => "Phone", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'street_address', // The db column name
            'label' => "Street Address", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'city', // The db column name
            'label' => "City", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'province', // The db column name
            'label' => "Province / State", // Table column heading
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

        $this->crud->addColumn([
            'name' => 'image', // The db column name
            'label' => "Image", // Table column heading
            'type' => 'image'
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
        CRUD::setValidation(PersonelRequest::class);

        //tab biodata
        $this->crud->addField([
            'label'     => 'Status', // Table column heading
            'type'      => 'select2',
            'name'      => 'acc_status_id', // the column that contains the ID of that connected entity;
            'entity'    => 'accountstatus', // the method that defines the relationship in your Model
            'attribute' => 'acc_status', // foreign key attribute that is shown to user
            'model'     => "App\Models\Accountstatus",
            'tab'       => 'Biodata',
        ]);

        $this->crud->addField([
            'label'     => 'Regional Council', // Table column heading
            'type'      => 'select2',
            'name'      => 'rc_dpw_id', // the column that contains the ID of that connected entity;
            'entity'    => 'rc_dpw', // the method that defines the relationship in your Model
            'attribute' => 'rc_dpw_name', // foreign key attribute that is shown to user
            'model'     => "App\Models\RcDpwList",
            'tab'       => 'Biodata',
        ]);

        $this->crud->addField([
            'label'     => 'Title', // Table column heading
            'type'      => 'select2',
            'name'      => 'title_id', // the column that contains the ID of that connected entity;
            'entity'    => 'title', // the method that defines the relationship in your Model
            'attribute' => 'short_desc', // foreign key attribute that is shown to user
            'model'     => "App\Models\TitleList",
            'tab'       => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'first_name',
            'label'           => "First Name",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'last_name',
            'label'           => "Last Name",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'church_name',
            'label'           => "Church Name",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'gender',
            'label'           => "Gender",
            'type'            => 'select2_from_array',
            'options'         => ["Male" => "Male", "Female" => "Female",],
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'  => 'date_of_birth',
            'type'  => 'date_picker',
            'label' => 'Date of Birth',
            'tab'   => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'            => 'marital_status',
            'label'           => "Marital Status",
            'type'            => 'select2_from_array',
            'options'         => ["Single" => "Single", "Married" => "Married",
                                "Divorce" => "Divorce", "Widower" => "Widower",],
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'spouse_name',
            'label'           => "Spouse Name",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'  => 'spouse_date_of_birth',
            'type'  => 'date_picker',
            'label' => 'Spouse Date of Birth',
            'tab'   => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'anniversary',
            'type'  => 'date_picker',
            'label' => 'Anniversary',
            'tab'   => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'            => 'child_name',
            'label'           => "Child Name",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'ministry_background',
            'label'           => "Ministry Background",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'name'            => 'career_background',
            'label'           => "Career Background",
            'type'            => 'text',
            'tab'             => 'Biodata',
        ]);

        $this->crud->addField([
            'label' => "Upload Your Photo & Family Photo (Max 3mb)",
            'name' => "image",
            'type' => 'image',
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab'  => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'password', // The db column name
            'label' => "Password", // Table column heading
            'type' => 'password',
            'tab'  => 'Biodata',
        ]);

        //tab contact
        $this->crud->addField([
            'name'            => 'street_address',
            'label'           => "Street Address",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'city',
            'label'           => "City",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'province',
            'label'           => "Province / State",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'postal_code',
            'label'           => "Postal Code",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'label'     => 'Country', // Table column heading
            'type'      => 'select2',
            'name'      => 'country_id', // the column that contains the ID of that connected entity;
            'entity'    => 'country', // the method that defines the relationship in your Model
            'attribute' => 'country_name', // foreign key attribute that is shown to user
            'model'     => "App\Models\CountryList",
            'tab'       => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'email',
            'label'           => "Email 1",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'second_email',
            'label'           => "Email 2",
            'type'            => 'text',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'phone',
            'label'           => "Phone",
            'type'            => 'number',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'            => 'fax',
            'label'           => "Fax",
            'type'            => 'number',
            'tab'             => 'Contact Information',
        ]);

        $this->crud->addField([
            'name'  => 'is_lifetime',
            'label' => 'Lifetime',
            'type'  => 'checkbox',
            'tab'   => 'Licensing Information',
        ]);
        
        //tab licensing information
        $this->crud->addField([
            'name'  => 'first_licensed_on',
            'type'  => 'date_picker',
            'label' => 'First Licensed On',
            'tab'   => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'            => 'card',
            'label'           => "Card ID",
            'type'            => 'text',
            'tab'             => 'Licensing Information',
        ]);

        $this->crud->addField([
            'name'  => 'valid_card_start',
            'type'  => 'date_picker',
            'label' => 'Valid Card Start',
            'tab'   => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'valid_card_end',
            'type'  => 'date_picker',
            'label' => 'Valid Card End',
            'tab'   => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'            => 'current_certificate_number',
            'label'           => "Current Certificate Number",
            'type'            => 'text',
            'tab'             => 'Licensing Information',
        ]);

        $this->crud->addField([
            'name'            => 'notes',
            'label'           => "Notes",
            'type'            => 'textarea',
            'tab'             => 'Licensing Information',
        ]);
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

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        return $this->traitStore();
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

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        return $this->traitUpdate();
    }

    public function show()
    {
        $this->crud->getCurrentEntry();
        $data['crud'] = $this->crud;
        return view('vendor.backpack.crud.showpersonel',$data);
    }
}
