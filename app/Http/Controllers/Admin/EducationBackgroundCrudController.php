<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EducationBackgroundRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EducationBackgroundCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EducationBackgroundCrudController extends CrudController
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
        CRUD::setModel(\App\Models\EducationBackground::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/educationbackground');
        CRUD::setEntityNameStrings('Education Background', 'Education Backgrounds');
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
            'name' => 'degree', // The db column name
            'label' => "Degree", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'type_education', // The db column name
            'label' => "Type", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'concentration_education', // The db column name
            'label' => "Concentration", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'school', // The db column name
            'label' => "School", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'year', // The db column name
            'label' => "Year", // Table column heading
            'type' => 'number'
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
        CRUD::setValidation(EducationBackgroundRequest::class);

        $this->crud->addField([
            'name'            => 'degree',
            'label'           => "Degree",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'type_education',
            'label'           => "Type",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'concentration_education',
            'label'           => "Concentration",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'school',
            'label'           => "School",
            'type'            => 'text',
        ]);

        $this->crud->addField([
            'name'            => 'year',
            'label'           => "Year",
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
