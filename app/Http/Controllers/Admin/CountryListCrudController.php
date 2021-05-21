<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CountryListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Imports\CountryListImport;
use Excel;

/**
 * Class CountryListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CountryListCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CountryList::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/countrylist');
        CRUD::setEntityNameStrings('Country', 'Country List');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addButtonFromView('top', 'uploadcountry', 'uploadcountry', 'beginning');

        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => "ID", // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'iso_two', // The db column name
            'label' => "Iso 2", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'iso_three', // The db column name
            'label' => "Iso 3", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'country_name', // The db column name
            'label' => "Country Name", // Table column heading
            'type' => 'text'
        ]);
    }

    public function uploadcountry(Request $request)
    {   
        // $messages = array(
        //     'same'    => 'Invalid File',
        // );
        // same:iso_two,iso_three,country_name
        
        $status = 'Successfully Done';
        $request->validate(['fileToUpload'=>'required|file|mimes:xls,xlsx']);
        Excel::import(new CountryListImport, request()->file('fileToUpload'));
        
        return back()->with(['status' => $status]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CountryListRequest::class);

        $this->crud->addField([
            'name' => 'iso_two',
            'type' => 'text',
            'label' => "Iso 2"
        ]);
        $this->crud->addField([
            'name' => 'iso_three',
            'type' => 'text',
            'label' => "Iso 3"
        ]);
        $this->crud->addField([
            'name' => 'country_name',
            'type' => 'text',
            'label' => "Country Name"
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
