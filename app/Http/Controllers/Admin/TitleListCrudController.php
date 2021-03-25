<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TitleListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TitleListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TitleListCrudController extends CrudController
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
        CRUD::setModel(\App\Models\TitleList::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/titlelist');
        CRUD::setEntityNameStrings('Title', 'Title List');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // $this->crud->setColumns(['id','short_desc', 'long_desc']);

        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => "ID", // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'short_desc', // The db column name
            'label' => "Short Description", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'long_desc', // The db column name
            'label' => "Long Description", // Table column heading
            'type' => 'text'
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
        CRUD::setValidation(TitleListRequest::class);

        $this->crud->addField([
            'name' => 'short_desc',
            'type' => 'text',
            'label' => "Short Description"
        ]);

        $this->crud->addField([
            'name' => 'long_desc',
            'type' => 'text',
            'label' => "Long Description"
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
