<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Personel;
use App\Models\PersonelImage;
use App\Models\StatusHistory;
use App\Models\StructureChurch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PersonelRequest;
use App\Http\Requests\PersonelUpdateRequest;
use App\Models\Appointment_history;
use App\Models\CareerBackgroundPastors;
use App\Models\ChildNamePastors;
use App\Models\Church;
use App\Models\EducationBackground;
use App\Models\MinistryBackgroundPastor;
use App\Models\MinistryRole;
use App\Models\Relatedentity;
use App\Models\SpecialRolePersonel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\PersonelsRcdpw;
use App\Helpers\HitApi;
use App\Helpers\HitCompare;
use Illuminate\Support\Str;

/**
 * Class PersonelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonelCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {search as traitSearch;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as traitStore;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    function setup()
    {
        CRUD::setModel(\App\Models\Personel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/personel');
        CRUD::setEntityNameStrings('Pastor', 'Pastors');
        $this->crud->fromPastor = true;
        $this->crud->leftColumns = 4;
        $this->crud->rightColumns = 1;
        // $this->crud->groupBy('id'); 
        // $this->crud->addClause('where', 'country_id', 101);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => 'No.',
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
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'last_name', // The db column name
            'label' => "Last Name", // Table column heading
            'type' => 'text',
        ]);

        // $this->crud->addColumn([
        //     'name' => 'acc_status', // The db column name
        //     'label' => "Status", // Table column heading
        //     'type' => 'closure',
        //     'function' => function ($entry) {
        //         return $entry->acc_status;
        //     },
        //     'searchLogic' => function ($query, $column, $searchTerm) {
        //         $query->orWhere(DB::raw('IFNULL(status_histories.acc_status, "-")'), 'LIKE', '%' . $searchTerm . '%');
        //     },
        //     'orderable' => true,
        //     'orderLogic' => function ($query, $column, $columnDirection) {
        //         return $query->orderBy(DB::raw('IFNULL(status_histories.acc_status, "-")'), $columnDirection);
        //     },
        // ]);

        $this->crud->addColumn([
            'name' => 'status', // The db column name
            'label' => "Status", // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->status;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere(DB::raw('IFNULL(status_histories.status, "-")'), 'LIKE', '%' . $searchTerm . '%');
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy(DB::raw('IFNULL(status_histories.status, "-")'), $columnDirection);
            },
        ]);

        // $this->crud->addColumn([
        //     'name' => 'rc_dpw', // The db column name
        //     'label' => "RC / DPW", // Table column heading
        //     'type' => 'relationship',
        //     'attribute' => 'rc_dpw_name',
        // ]);

        // $this->crud->addColumn([
            
        //     // 1-n relationship
        //     'label'     => 'RC / DPW', // Table column heading
        //     'type'      => 'select',
        //     'name'      => 'personels_id', // the column that contains the ID of that connected entity;
        //     'entity'    => 'pivod_rcdpw', // the method that defines the relationship in your Model
        //     'attribute' => 'rc_dpw_name', // foreign key attribute that is shown to user
        //     'model'     => "App\Models\RcDpwList", // foreign key model
        //     'searchLogic' => function($query, $collumn, $searchTerm){
        //         $query->orWhereHas('pivod_rcdpw', function($query) use($searchTerm){
        //             $query->where('rc_dpw_name', 'LIKE', "%{$searchTerm}%");
        //         });
        //     }
             
        // ]);

        $this->crud->addColumn([
            'name' => 'rc_dpw_name',
            'label' => 'RC / DPW',
            'type' => 'closure',
            'function' => function($e){
                $str = $e->pivod_rcdpw->implode('rc_dpw_name', ', ');
                return Str::limit($str, 40);
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('pivod_rcdpw', function ($q) use ($column, $searchTerm) {
                    $q->where('rc_dpw_name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        // $this->crud->addColumn([
        //     'name' => 'church_name', // The db column name
        //     'label' => "Church Name", // Table column heading
        //     'type' => 'text',
        // ]);

        $this->crud->addColumn([
            'name' => 'email', // The db column name
            'label' => "Email", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'second_email', // The db column name
            'label' => "Email (Secondary)", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'phone', // The db column name
            'label' => "Phone", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'street_address', // The db column name
            'label' => "Address", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'city', // The db column name
            'label' => "City", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'province', // The db column name
            'label' => "State", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'postal_code', // The db column name
            'label' => "Postcode", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'country', // The db column name
            'label' => "Country", // Table column heading
            'type' => 'relationship',
            'attribute' => 'country_name',
        ]);

        $this->crud->addColumn([
            'name' => 'card', // The db column name
            'label' => "Card ID", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'current_certificate_number', // The db column name
            'label' => "Certificate Number	", // Table column heading
            'type' => 'text',
        ]);

        // $this->crud->addColumn([
        //     'name' => 'image', // The db column name
        //     'label' => "Image", // Table column heading
        //     'type' => 'image',
        // ]);

    }

    function search()
    {
        $subQuery = StatusHistory::leftJoin('status_histories as temps', function ($leftJoin) {
            $leftJoin->on('temps.personel_id', 'status_histories.personel_id')
                ->where(function ($innerQuery) {
                    $innerQuery->whereRaw('status_histories.date_status < temps.date_status')
                        ->orWhere(function ($deepestQuery) {
                            $deepestQuery->whereRaw('status_histories.date_status = temps.date_status')
                                ->whereRaw('status_histories.id < temps.id');
                        });
                });
        })->whereNull('temps.id')
            // ->join('account_status', 'account_status.id', 'status_histories.status_histories_id')
            // ->select('status_histories.personel_id', 'account_status.acc_status');
        ->select('status_histories.personel_id', 'status_histories.status');
        $this->crud->query->leftJoinSub($subQuery, 'status_histories', function ($leftJoinSub) {
            $leftJoinSub->on('personels.id', 'status_histories.personel_id');
        })
        // ->select('personels.*', DB::raw('IFNULL(status_histories.acc_status, "-") as acc_status'));
        ->select('personels.*', DB::raw('IFNULL(status_histories.status, "-") as status'));
        return $this->traitSearch();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    function setupCreateOperation($type = null)
    {
        CRUD::setValidation(PersonelRequest::class);

        //tab biodata

        // $this->crud->addField([
        //     'label' => 'Regional Council', // Table column heading
        //     'type' => 'select2',
        //     'name' => 'rc_dpw_id', // the column that contains the ID of that connected entity;
        //     'entity' => 'rc_dpw', // the method that defines the relationship in your Model
        //     'attribute' => 'rc_dpw_name', // foreign key attribute that is shown to user
        //     'model' => "App\Models\RcDpwList",
        //     'tab' => 'Biodata',
        // ]);

        $this->crud->addField([    // Select2Multiple = n-n relationship (with pivot table)
            'label'     => "Regional Council",
            'type'      => 'select2_multiple',
            'name'      => 'pivod_rcdpw', // the method that defines the relationship in your Model
       
            // optional
            'entity'    => 'pivod_rcdpw', // the method that defines the relationship in your Model
            'model'     => "App\Models\RcDpwList", // foreign key model
            'attribute' => 'rc_dpw_name', // foreign key attribute that is shown to user
            'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
            'tab' => 'Biodata',
            // 'select_all' => true, // show Select All and Clear buttons?
        ]);

        $this->crud->addField([
            'label' => 'Title', // Table column heading
            'type' => 'select2',
            'name' => 'title_id', // the column that contains the ID of that connected entity;
            'entity' => 'title', // the method that defines the relationship in your Model
            'attribute' => 'short_desc', // foreign key attribute that is shown to user
            'model' => "App\Models\TitleList",
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'first_name',
            'label' => "First Name",
            'type' => 'text',
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'last_name',
            'label' => "Last Name",
            'type' => 'text',
            'tab' => 'Biodata',
        ]);

        // $this->crud->addField([
        //     'name' => 'church_name',
        //     'label' => "Church Name",
        //     'type' => 'text',
        //     'tab' => 'Biodata',
        // ]);

        $this->crud->addField([
            'name' => 'gender',
            'label' => "Gender",
            'type' => 'select2_from_array',
            'options' => ["Male" => "Male", "Female" => "Female"],
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'date_of_birth',
            'type' => 'date_picker',
            'label' => 'Date of Birth',
            'tab' => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
        ]);

        $this->crud->addField([
            'name' => 'marital_status',
            'label' => "Marital Status",
            'type' => 'select2_from_array',
            'options' => ["Single" => "Single", "Married" => "Married",
                "Divorce" => "Divorce", "Widower" => "Widower"],
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'spouse_name',
            'label' => "Spouse Name",
            'type' => 'text',
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'spouse_date_of_birth',
            'type' => 'date_picker',
            'label' => 'Spouse Date of Birth',
            'tab' => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
        ]);

        $this->crud->addField([
            'name' => 'anniversary',
            'type' => 'date_picker',
            'label' => 'Anniversary',
            'tab' => 'Biodata',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
        ]);

        $this->crud->addField([
            'label' => "Upload Profile Photo (Max 3mb)",
            'name' => "profile_image",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'label' => "Upload Family Photo (Max 3mb)",
            'name' => "family_image",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'label' => "Upload Misc Photo (Max 3mb)",
            'name' => "misc_image",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab' => 'Biodata',
        ]);

        $this->crud->addField([
            'name' => 'password', // The db column name
            'label' => "Password", // Table column heading
            'type' => 'password',
            'tab' => 'Biodata',
        ]);

        //tab contact
        $this->crud->addField([
            'name' => 'street_address',
            'label' => "Address",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'city',
            'label' => "City",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'province',
            'label' => "State",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'postal_code',
            'label' => "Postcode",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'label' => 'Country', // Table column heading
            'type' => 'select2',
            'name' => 'country_id', // the column that contains the ID of that connected entity;
            'entity' => 'country', // the method that defines the relationship in your Model
            'attribute' => 'country_name', // foreign key attribute that is shown to user
            'model' => "App\Models\CountryList",
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => "Email",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'second_email',
            'label' => "Email (Secondary)",
            'type' => 'text',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'label' => "Phone",
            'type' => 'number',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'fax',
            'label' => "Mobile Phone",
            'type' => 'number',
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'language',
            'label' => "Language",
            'type' => 'select2_from_array',
            'options' => collect(Personel::$arrayLanguage)->mapWithKeys(function($lang){
                return [$lang => $lang];
            }),
            'tab' => 'Contact Information',
        ]);

        $this->crud->addField([
            'name' => 'is_lifetime',
            'label' => 'Lifetime',
            'type' => 'checkbox_lifetime',
            'tab' => 'Licensing Information',
        ]);

        //tab licensing information
        $this->crud->addField([
            'name' => 'first_licensed_on',
            'type' => 'date_picker',
            'label' => 'First Licensed On',
            'tab' => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
        ]);

        $this->crud->addField([
            'name' => 'card',
            'label' => "Card ID",
            'type' => 'text',
            'tab' => 'Licensing Information',
        ]);

        $this->crud->addField([
            'name' => 'valid_card_start',
            'type' => 'date_picker',
            'label' => 'Valid Card Start',
            'tab' => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
        ]);

        $this->crud->addField([
            'name' => 'valid_card_end',
            'type' => 'date_picker_valid_card_end',
            'label' => 'Valid Card End',
            'tab' => 'Licensing Information',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'en',
            ],
            'attributes' => [
                'required' => true,
                ]
        ]);

        $this->crud->addField([
            'name' => 'current_certificate_number',
            'label' => "Current Certificate Number",
            'type' => 'text',
            'tab' => 'Licensing Information',
        ]);

        $this->crud->addField([
            'label' => "Upload Certificate (Max 3mb)",
            'name' => "certificate",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab' => 'Licensing Information',
        ]);

        $this->crud->addField([
            'label' => "Upload ID Card (Max 3mb)",
            'name' => "id_card",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            'tab' => 'Licensing Information',
        ]);

        $this->crud->addField([
            'name' => 'notes',
            'label' => "Notes",
            'type' => 'textarea',
            'tab' => 'Licensing Information',
        ]);


        if($type == 'edit'){
            $this->crud->addField([   // repeatable
                'name'  => 'church_name',
                'label' => 'Leadership',
                'type'  => 'repeatable',
                'tab' => 'Leadership Structure',
                'fields' => [
                    [
                        'label'     => "Church Name",
                        'type'      => 'select2_from_array',
                        'name'      => 'church_id', // the column that contains the ID of that connected entity;
                        'options'   => $this->getChurch(),
                        'allows_null' => true,
                    ],
                    [
                        'label'     => "Role",
                        'type'      => 'select2_from_array',
                        'name'      => 'title_structure_id', // the column that contains the ID of that connected entity;
                        'options'   => $this->getMinistryRole(),
                        'allows_null' => true,
                    ],
                ],
            
                // optional
                'new_item_label'  => 'Add Leadership', // customize the text of the button
                'init_rows' => 1, // number of empty rows to be initialized, by default 1
            ]);

        }else{
            $this->crud->addField([   // repeatable
                'name'  => 'church_name',
                'label' => 'Leadership',
                'type'  => 'repeatable',
                'tab' => 'Leadership Structure',
                'fields' => [
                    [
                        'label'     => "Church Name",
                        'type'      => 'select2_from_array',
                        'name'      => 'church_id', // the column that contains the ID of that connected entity;
                        'options'   => $this->getChurch(),
                        'allows_null' => true,
                    ],
                    [
                        'label'     => "Role",
                        'type'      => 'select2_from_array',
                        'name'      => 'title_structure_id', // the column that contains the ID of that connected entity;
                        'options'   => $this->getMinistryRole(),
                        'allows_null' => true,
                    ],
                ],
            
                // optional
                'new_item_label'  => 'Add Leadership', // customize the text of the button
                'init_rows' => 1, // number of empty rows to be initialized, by default 1
            ]);
        }

        
    }

    public function store(Request $request)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', $request->input('password'));
        } else {
            $request->request->remove('password');
        }
        if ($request->input('is_lifetime') == 1) {
            $request->request->set('valid_card_end',null);
        }

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        DB::beginTransaction();
        try {
            $isDuplicate = Personel::query();

            if (!$request->filled('first_name')) {
                $isDuplicate->whereNull('first_name');
            } else {
                $isDuplicate->where('first_name', $request->first_name);
            }

            if (!$request->filled('last_name')) {
                $isDuplicate->whereNull('last_name');
            } else {
                $isDuplicate->where('last_name', $request->last_name);
            }
            // if (!$request->filled('church_name')) {
            //     $isDuplicate->whereNull('church_name');
            // } else {
            //     $isDuplicate->where('church_name', $request->church_name);
            // }

            if (!$request->filled('date_of_birth')) {
                $isDuplicate->whereNull('date_of_birth');
            } else {
                $isDuplicate->where('date_of_birth', $request->date_of_birth);
            }

            $isDuplicate = $isDuplicate->select('id')->first();

            if(isset($request->church_name) && ($request->church_name != '[]'))
            {
                $d = json_decode($request->church_name);
                for($i = count($d) - 1; $i > 0; $i--){
                    $now = $d[$i];
                    for($u = $i - 1; $u >= 0; $u--){
                        $before = $d[$u];
                        if(($now->church_id == $before->church_id) && ($now->title_structure_id == $before->title_structure_id)){
                            $errors['church_name'] = ["The pastor with same church Name has already exists."];
                        }
                    }
                }
            }

            if ($isDuplicate != null) {
                // DB::rollback();
                $errors_ = [
                    'first_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                    'last_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                    // 'church_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                    'date_of_birth' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                ];
                $errors = array_merge($errors, $errors_);
                // return redirect($this->crud->route . '/create')
                //                 ->withInput()->withErrors($errors);
            }

            if(count($errors) > 0){
                 DB::rollback();
                 return redirect($this->crud->route . '/create')
                                ->withInput()->withErrors($errors);
            }




            // $result = $this->checkMultipleImage($request, null);
            // if(count($result['errors']) != 0){
            //     DB::rollback();
            //     return redirect($this->crud->route . '/create')->withInput()
            //     ->withErrors($result['errors']);
            // }
            // insert item in the db
            $item = $this->crud->create($this->crud->getStrippedSaveRequest());
            $this->data['entry'] = $this->crud->entry = $item;
            if ($request->input("church_name")) {
                $leaderships = json_decode($request->input("church_name"));
                // dd($leaderships);

                foreach ($leaderships as $key => $leadership) {
                    if ( $leadership->title_structure_id && $leadership->church_id) {
                        $insert_p = new StructureChurch();
                        $insert_p->title_structure_id = $leadership->title_structure_id;
                        $insert_p->churches_id = $leadership->church_id;
                        $insert_p->personel_id = $item->id;
                        $insert_p->save();
                    }
                }
            }
            
            DB::commit();

            // hit api for update personel
            $send = new HitApi;
            $id = [$item->getKey()];
            $module = 'user';
            $response = $send->action($id, 'create', $module)->json();

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    function setupUpdateOperation()
    {
        $this->setupCreateOperation($type = 'edit');
        CRUD::setValidation(PersonelUpdateRequest::class);
    }

    function update($id)
    {
        $this->crud->setRequest($this->crud->validateRequest());

        // $this->crud->removeField('password_confirmation');

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password',$request->input('password'));
        } else {
            $request->request->remove('password');
        }

        if ($request->input('is_lifetime') == 1) {
            $request->request->set('valid_card_end',null);
        }

        $errors = [];

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        DB::beginTransaction();
        try {
            $model = Personel::where('id', $id)->firstOrFail();

            $churches = StructureChurch::where('personel_id', $id)->get();
            $arr_unit = [];

            foreach ($churches as $key => $churche) {
                $arr_unit[] = ['title_structure_id' => $churche->title_structure_id, 'church_id' =>$churche->churches_id];
            }

            $item_previous = $model->toArray(); // adalah data sebelumnya
            
            $hitCompare = new HitCompare;
            $hitCompare->addFieldCompare(
                [
                    'first_name' => 'first_name',
                    'last_name' => 'last_name',
                    'profile_image' => 'profile_image',
                    'phone' => 'phone',
                    'email' => 'email',
                ], 
            $request->all());

           $com = $hitCompare->compareData($item_previous);
        //    dd($com);

            // $result = $this->checkMultipleImage($request, $model);
            // if(count($result['errors']) != 0){
            //     DB::rollback();
            //     return redirect($this->crud->route . '/' . $id . '/edit')->withInput()
            //     ->withErrors($result['errors']);
            // }

            $isDuplicate = Personel::query();

            if (!$request->filled('first_name')) {
                $isDuplicate->whereNull('first_name');
            } else {
                $isDuplicate->where('first_name', $request->first_name);
            }

            if (!$request->filled('last_name')) {
                $isDuplicate->whereNull('last_name');
            } else {
                $isDuplicate->where('last_name', $request->last_name);
            }
            
            if (!$request->filled('date_of_birth')) {
                $isDuplicate->whereNull('date_of_birth');
            } else {
                $isDuplicate->where('date_of_birth', $request->date_of_birth);
            }

            $trigger_matches_church = 0;

            $churchIds = $request->church_id;
            if ($churchIds) {
                foreach ($churchIds as $key => $cid) {
                    $existData = StructureChurch::where('personel_id', $id)->where('churches_id', $cid)->exists();
                    if ($existData) {
                        $errors['church_name'] = ['The pastor with same church Name has already exists.'];
                    }
                }
            }

            // dd($request->church_name, json_decode($request->church_name)[0]->church_id);

            if(isset($request->church_name) && ($request->church_name != '[]')){
                $d = json_decode($request->church_name);
                for($i = count($d) - 1; $i > 0; $i--){
                    $now = $d[$i];
                    for($u = $i - 1; $u >= 0; $u--){
                        $before = $d[$u];
                        if(($now->church_id == $before->church_id) && ($now->title_structure_id == $before->title_structure_id)){
                            $errors['church_name'] = ["The pastor with same church Name has already exists."];
                        }
                    }
                }

                if(!isset($errors['church_name'])){
                    $count_structure_church = $churches->count();
                    $index_same_churces = 0;
                    foreach($d as $d_){
                        $request_structure_church = $d_;
                        foreach($churches as $church){
                            if(($request_structure_church->church_id == $church->churches_id) && ($request_structure_church->title_structure_id == $church->title_structure_id)){
                                $index_same_churces++;
                                break;
                            }
                        }
                    }

                    if(($index_same_churces != $count_structure_church) || (count($d) != $count_structure_church)){
                        $trigger_matches_church = 1;
                    }

                }

            }else{
                // jika request kosongan
                $count_structure_church = $churches->count();
                if($count_structure_church > 0){
                    // dan ternyata sebelumnya ada
                    $trigger_matches_church = 1;
                }
            }


            

            /*
            
            if($request->church_name != '[]' || $request->church_name !== '[]'){
                
                if(preg_match_all('/(\{[\:\"\_\,a-z0-9]+\})/', $model->church_name, $matches)) {
                    $church_name = $matches[1];
                }
    
                if(preg_match_all('/(\{[\:\"\_\,a-z0-9]+\})/', $request->input("church_name"), $matches)) {
                    $input_church_name = $matches[1];
                }
    
                if(is_array($input_church_name) && is_array($church_name)){
                    foreach($input_church_name as $church){
                        if(!in_array($church, $church_name)){
                            $trigger_matches_church = 1;
                        }

                    }
                }else{
                    $trigger_matches_church = 1;
                }
    
                $count_church_name_data = array_count_values($input_church_name);
    
    
                // check validation double data church_name
    
                
                if($count_church_name_data){
                    foreach($count_church_name_data as $key => $number){
                        if($number > 1){
                            $errors['church_name'] = ['The pastor with same church Name has already exists.'];
                        }
                    }
                }
            }
            */

            $isDuplicate = $isDuplicate->select('id')->first();

            if ($isDuplicate != null && $isDuplicate->id != $id) {
                // DB::rollback();
                // $errors = [
                //     'first_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                //     'last_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                //     // 'church_name' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                //     'date_of_birth' => ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'],
                // ];
                $errors['first_name'] = ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'];
                $errors['last_name'] = ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'];
                $errors['date_of_birth'] = ['The pastor with same First Name, Last Name, Church Name and Date of Birth has already exists.'];

                // return redirect($this->crud->route . '/'. $id . '/edit')
                //     ->withInput()->withErrors($errors);
            }

            if(count($errors) > 0){
                DB::rollback();
                return redirect($this->crud->route . '/'. $id . '/edit')
                ->withInput()->withErrors($errors);
            }

            if (json_encode($arr_unit) != $request->input("church_name")) {
                StructureChurch::where('personel_id', $model->id)->delete();
                $leaderships = json_decode($request->input("church_name"));
                foreach ($leaderships as $key => $leadership) {
                    if ( $leadership->title_structure_id && $leadership->church_id) {
                        $insert_p = new StructureChurch();
                        $insert_p->title_structure_id = $leadership->title_structure_id;
                        $insert_p->churches_id = $leadership->church_id;
                        $insert_p->personel_id = $model->id;
                        $insert_p->save();
                    }
                }
            }

            
            // update the row in the db
            $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest());
            $this->data['entry'] = $this->crud->entry = $item;

            DB::commit();

            // hit api for update user
            if($com || ($trigger_matches_church == 1)){
                $send = new HitApi;
                $id = [$com];
                $module = 'user';
                $response = $send->action($id, 'update', $module)->json();
            }

        } catch (Exception $e) { 
            DB::rollback();
            throw $e;
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction(); 

        return $this->crud->performSaveAction($item->getKey());
    }


    // public function checkMultipleImage($request, $item){
    //     $images = $request->image;
    //     $labels = $request->image_label ?? [];
    //     $imageIds = $request->image_ids ?? [];
    //     $imageChanges = $request->image_change ?? [];
    //     $validIds = [];
    //     $validImages = [];
    //     $errors = [];
    //     if($images != null && count($images) > 0){
    //         $i = 0;
    //         foreach($images as $index => $image){
    //             $i++;
    //             $id = $imageIds[$index] ?? null;
    //             $validId = false;
    //             if($id != null && $item != null && PersonelImage::where('personel_id', $item->id)->where('id', $id)->exists()){
    //                 $validIds[$id] = true;
    //                 $validId = true;
    //             }
    //             $label = $labels[$index] ?? '';
    //             if(strlen(trim($label)) == 0){
    //                 $errors['image_label.' . $index] = [trans('validation.required', ['attribute' => 'image label ' . $i])];
    //             }
    //             else{
    //                 $imageChange = $imageChanges[$index] ?? false;
    //                 $validImages[] = [
    //                     'id' => $validId ? $id : null,
    //                     'image' => $image,
    //                     'label' => $label,
    //                     'image_change' => $item != null && $imageChange
    //                 ];
    //             }
    //         }
    //     }
    //     return [
    //         'valid_ids' => array_keys($validIds),
    //         'valid_images' => $validImages,
    //         'errors' => $errors
    //     ];
    // }

    function show()
    {
        $this->crud->getCurrentEntry();
        // $churches = StructureChurch::where('personel_id', $this->crud->getCurrentEntry()->id)
        //             ->join('churches', 'churches.id', 'structure_churches.churches_id')
        //             ->join('churches', 'churches.id', 'structure_churches.churches_id')
        //             ->join('rc_dpwlists', 'rc_dpwlists.id', 'churches.rc_dpw_id')
        //             ->get(['churches.*', 'rc_dpwlists.rc_dpw_name', 'structure_churches.']);
        $churches = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                    ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                    ->join('title_lists', 'title_lists.id', 'personels.title_id')
                    ->join('churches', 'churches.id', 'structure_churches.churches_id')
                    ->where('structure_churches.personel_id', $this->crud->getCurrentEntry()->id)
                    ->get(['structure_churches.id as id', 'ministry_roles.ministry_role as ministry_role', 
                    'title_lists.short_desc','churches.church_name', 'churches.id as church_id', 'churches.church_address','title_lists.long_desc','personels.first_name', 'personels.last_name']);

        // $current_statuses = StatusHistory::where('personel_id', $this->crud->getCurrentEntry()->id)
        //                     ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
        //                     ->orderBy('date_status','desc')
        //                     ->orderBy('status_histories.created_at','desc')
        //                     ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);
        
        $current_statuses = StatusHistory::where('personel_id', $this->crud->getCurrentEntry()->id)
        ->orderBy('date_status','desc')
        ->orderBy('created_at','desc')
        ->get(['id', 'date_status', 'created_at', 'status', 'reason']);

        $data['crud'] = $this->crud;
        $data['current_status'] = (sizeof($current_statuses)>0)?$current_statuses->first()->status:"-";
        $data['current_statuses'] = $current_statuses;
        $data['churches'] = $churches;

        return view('vendor.backpack.crud.showpersonel', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $fields = $this->crud->getUpdateFields();
        // $personelImages = PersonelImage::where('personel_id', $id)->select('id', 'label', 'image')->get();
        // $fields['image']['value'] = $personelImages->toArray();
        $churches = StructureChurch::where('personel_id', $id)->get();
        $churches = $churches->map(function($item, $key){
            return [
                'title_structure_id' => $item['title_structure_id'],
                'church_id' => $item['churches_id']
            ];
        });
        $fields['church_name']['value'] = $churches->all();

        $this->crud->setOperationSetting('fields', $fields);
        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        DB::beginTransaction();
        try{
            if(PersonelsRcdpw::where('personels_id', $id)->exists()){
                PersonelsRcdpw::where('personels_id', $id)->delete();
            }
            if(Appointment_history::where('personel_id', $id)->exists()){
                Appointment_history::where('personel_id', $id)->delete();
            }
            if(SpecialRolePersonel::where('personel_id', $id)->exists()){
                SpecialRolePersonel::where('personel_id', $id)->delete();
            }
            if(Relatedentity::where('personel_id', $id)->exists()){
                Relatedentity::where('personel_id', $id)->delete();
            }
            if(EducationBackground::where('personel_id', $id)->exists()){
                EducationBackground::where('personel_id', $id)->delete();
            }
            if(ChildNamePastors::where('personel_id', $id)->exists()){
                ChildNamePastors::where('personel_id', $id)->delete();
            }
            if(MinistryBackgroundPastor::where('personel_id', $id)->exists()){
                MinistryBackgroundPastor::where('personel_id', $id)->delete();
            }
            if(CareerBackgroundPastors::where('personel_id', $id)->exists()){
                CareerBackgroundPastors::where('personel_id', $id)->delete();
            }
            if(StatusHistory::where('personel_id', $id)->exists()){
                StatusHistory::where('personel_id', $id)->delete();
            }
            if(PersonelImage::where('personel_id', $id)->exists()){
                PersonelImage::where('personel_id', $id)->delete();
            }
            if(PersonalAccessToken::where('tokenable_id', $id)->exists()){
                PersonalAccessToken::where('tokenable_id', $id)->delete();
            }
            if(StructureChurch::where('personel_id', $id)->exists()){
                StructureChurch::where('personel_id', $id)->delete();
            }

            $response = $this->crud->delete($id);
            DB::commit();
             // hit api for update user
             $send = new HitApi;
             $ids = [$id];
             $module = 'user';
             $response_json = $send->action($ids, 'delete', $module)->json();

            return $response;
        }
        catch(Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    private function getChurch(){
        $churches = Church::get();
        $arr_churches = [];
        foreach ($churches as $key => $value) {
            $arr_churches[$value->id] = $value->church_name;
        }

        return $arr_churches;
    }

    private function getMinistryRole(){
        $churches = MinistryRole::get();
        $arr_churches = [];
        foreach ($churches as $key => $value) {
            $arr_churches[$value->id] = $value->ministry_role;
        }

        return $arr_churches;
    }

}
