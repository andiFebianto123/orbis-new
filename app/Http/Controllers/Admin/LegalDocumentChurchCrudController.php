<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LegalDocumentChurchRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LegalDocumentChurchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LegalDocumentChurchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LegalDocumentChurch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/legaldocumentchurch');
        CRUD::setEntityNameStrings('Legal Document', 'Legal Document Churches');
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
            'name' => 'legal_document_id', // The db column name
            'label' => "Documents", // Table column heading
            'type' => 'relationship',
            'attribute' => 'documents',
        ]);

        $this->crud->addColumn([
            'name' => 'number_document', // The db column name
            'label' => "Number", // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'issue_date', // The db column name
            'label' => "Issue Date", // Table column heading
            'type' => 'date'
        ]);
        
        $this->crud->addColumn([
            'name' => 'exp_date', // The db column name
            'label' => "Exp Date", // Table column heading
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'name' => 'status_document', // The db column name
            'label' => "Status Document", // Table column heading
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
        CRUD::setValidation(LegalDocumentChurchRequest::class);

        $this->crud->addField([
            'label'     => 'Document', // Table column heading
            'type'      => 'select2',
            'name'      => 'legal_document_id', // the column that contains the ID of that connected entity;
            'entity'    => 'legal_document_church', // the method that defines the relationship in your Model
            'attribute' => 'documents', // foreign key attribute that is shown to user
            'model'     => "App\Models\LegalDocument",
        ]);

        $this->crud->addField([
            'name'            => 'number_document',
            'label'           => "Number",
            'type'            => 'number',
        ]);

        $this->crud->addField([
            'name'  => 'issue_date',
            'type'  => 'date_picker',
            'label' => 'Issue Date',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'exp_date',
            'type'  => 'date_picker',
            'label' => 'Exp Date',

            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
                'language' => 'en'
            ],
        ]);

        $this->crud->addField([
            'name'            => 'status_document',
            'label'           => "Status",
            'options'         => ['Active' => "Active", 'Non Active' => "Non Active"],
            'type'            => 'select2_from_array',
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
