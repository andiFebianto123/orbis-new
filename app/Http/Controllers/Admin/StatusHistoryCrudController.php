<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StatusHistoryRequest;
use App\Models\Personel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Helpers\HitApi;
use App\Helpers\HitCompare;
use App\Models\StatusHistory;

/**
 * Class StatusHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StatusHistoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

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
        $this->crud->currentId = request()->personel_id;
        $this->crud->redirectTo = backpack_url('personel/'.$this->crud->currentId.'/show');
        $isPersonelExists =  Personel::where('id',$this->crud->currentId)->first();
        if($isPersonelExists == null){
            abort(404);
        }
        $this->crud->saveOnly=true;
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
            'name' => 'accountstatushistories', // The db column name
            'label' => "Status", // Table column heading
            'type' => 'relationship',
            'attribute' => 'acc_status',
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
            'label'     => 'Status', // Table column heading
            'type'      => 'select2',
            'name'      => 'status_histories_id', // the column that contains the ID of that connected entity;
            'entity'    => 'accountstatushistories', // the method that defines the relationship in your Model
            'attribute' => 'acc_status', // foreign key attribute that is shown to user
            'model'     => "App\Models\Accountstatus",
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

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $current_statuses_now = StatusHistory::where('personel_id', $request->personel_id)
                            ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                            // ->orderBy('date_status','desc')
                            ->orderBy('status_histories.created_at','desc')
                            ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);
        $status_now = (sizeof($current_statuses_now)>0)?$current_statuses_now->first()->acc_status:"-";
        // dd($status);

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        $current_statuses = StatusHistory::where('personel_id', $request->personel_id)
        ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
        // ->orderBy('date_status','desc')
        ->orderBy('status_histories.created_at','desc')
        ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);

        $status_new = (sizeof($current_statuses)>0)?$current_statuses->first()->acc_status:"-";

        if($status_now != $status_new){
            $send = new HitApi;
            $id = [$item->personel_id];
            $module = 'user_admin';
            $response = $send->action($id, 'update', $module)->json();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        return redirect(backpack_url('personel/'.$item->personel_id.'/show')); 
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $id = $request->get($this->crud->model->getKeyName());

        $item_previous = $this->crud->getEntry($id)->toArray(); // adalah data sebelumnya

        $item_previous['id'] = (int) $item_previous['personel_id'];

        $current_statuses_now = StatusHistory::where('personel_id', $request->personel_id)
                            ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                            // ->orderBy('date_status','desc')
                            ->orderBy('status_histories.created_at','desc')
                            ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);
        $status_now = (sizeof($current_statuses_now)>0)?$current_statuses_now->first()->acc_status:"-";
        // $hitCompare = new HitCompare;
        // $hitCompare->addFieldCompare(
        //     [
        //         'status_histories_id' => 'status_histories_id'
        //     ], 
        // $request->all());

        // $com = $hitCompare->compareData($item_previous);

        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        $current_statuses = StatusHistory::where('personel_id', $request->personel_id)
        ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
        // ->orderBy('date_status','desc')
        ->orderBy('status_histories.created_at','desc')
        ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);

        $status_new = (sizeof($current_statuses)>0)?$current_statuses->first()->acc_status:"-";


        if($status_now != $status_new){
            $send = new HitApi;
            $id = [$id];
            $module = 'user_admin';
            $response = $send->action($id, 'update', $module)->json();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return redirect(backpack_url('personel/'.$item->personel_id.'/show'));    
    }

    public function getCurrentId(){
        return Route::current()->parameter('personel_id');
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $item = $this->crud->getEntry($id);

        $current_statuses_now = StatusHistory::where('personel_id', $item->personel_id)
                            ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                            ->orderBy('date_status','desc')
                            ->orderBy('status_histories.created_at','desc')
                            ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);

        $current_statuses_now = (sizeof($current_statuses_now)>0)?$current_statuses_now->first()->acc_status:"-";

        $delete_data = $this->crud->delete($id);

        $current_statuses = StatusHistory::where('personel_id', $item->personel_id)
        ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
        ->orderBy('date_status','desc')
        ->orderBy('status_histories.created_at','desc')
        ->get(['status_histories.id as id', 'date_status', 'status_histories.created_at', 'acc_status', 'reason']);

        $current_statuses = (sizeof($current_statuses)>0)?$current_statuses->first()->acc_status:"-";

        if($current_statuses_now != $current_statuses){
            $send = new HitApi;
            $id = [$item->personel_id];
            $module = 'user_admin';
            $response = $send->action($id, 'update', $module)->json();
        }

        return $delete_data;

    }

}
