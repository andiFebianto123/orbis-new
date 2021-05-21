<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RcDpwListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Imports\RcdpwListImport;
use Excel;

/**
 * Class RcDpwListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RcDpwListCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RcDpwList::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/rcdpwlist');
        CRUD::setEntityNameStrings('RC / DPW', 'Regional Council / DPW List');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromView('top', 'uploadrcdpw', 'uploadrcdpw', 'beginning');

        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => "ID", // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'rc_dpw_name', // The db column name
            'label' => "Regional Council / DPW Names", // Table column heading
            'type' => 'text'
        ]);
    }

    public function uploadrcdpw(Request $request)
    {
        // $messages = array(
        //     'same'    => 'Invalid File',
        // );
        // same:rc_dpw_name

        $status = 'Successfully Done';
        $request->validate(['fileToUpload'=>'required|file|mimes:xls,xlsx']);
        Excel::import(new RcdpwListImport, request()->file('fileToUpload'));

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
        CRUD::setValidation(RcDpwListRequest::class);

        $this->crud->addField([
            'name' => 'rc_dpw_name',
            'type' => 'text',
            'label' => "Regional Council / DPW Names"
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
