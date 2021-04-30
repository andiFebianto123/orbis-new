<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DashboardRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Church;
use App\Models\Personel;
use App\Models\SpecialRolePersonel;
use App\Models\StructureChurch;
use App\Models\LegalDocumentChurch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Dashboard::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dashboard');
        CRUD::setEntityNameStrings('dashboard', 'Dashboard');
    }

    public function index()
    {
        $church_count = Church::count();
        $personel_count = Personel::where('acc_status_id', '1')
                    ->select('first_name', DB::raw('count(first_name) as total'))
                    ->groupBy('first_name')
                    ->get();
        $today_birthday = Personel::whereDay('date_of_birth', Carbon::now()->day)
                    ->select('first_name', DB::raw('count(first_name) as total'))
                    ->groupBy('first_name')
                    ->get();
        $country_tables = Church::join('country_lists','churches.country_id','country_lists.id')
                 ->select('country_name', DB::raw('count(country_name) as total'))
                 ->groupBy('country_name')
                 ->get();
        $type_tables = Church::join('church_types','churches.church_type_id','church_types.id')
                 ->select('entities_type', DB::raw('count(entities_type) as total'))
                 ->groupBy('entities_type')
                 ->get();
        $personel_tables = Personel::join('title_lists','personels.title_id','title_lists.id')
                 ->select('long_desc', DB::raw('count(long_desc) as total'))
                 ->groupBy('long_desc')
                 ->get();
        $rcdpw_tables = Church::join('rc_dpwlists','churches.rc_dpw_id','rc_dpwlists.id')
                 ->select('rc_dpw_name', DB::raw('count(rc_dpw_name) as total'))
                 ->groupBy('rc_dpw_name')
                 ->get();
        $personel_vip_tables = SpecialRolePersonel::join('special_roles','special_role_personels.special_role_id','special_roles.id')
                 ->select('special_role', DB::raw('count(special_role) as total'))
                 ->groupBy('special_role')
                 ->get();
        $ministry_role_tables = StructureChurch::join('ministry_roles','structure_churches.title_structure_id','ministry_roles.id')
                 ->select('ministry_role', DB::raw('count(ministry_role) as total'))
                 ->groupBy('ministry_role')
                 ->get();
        $pastors_birthday_tables = Personel::whereMonth('date_of_birth', Carbon::now()->month)
                    ->get();
        $pastors_anniversary_tables = Personel::whereMonth('anniversary', Carbon::now()->month)
                    ->where('marital_status', 'married')
                    ->get();
        $id_card_expiration_tables = Personel::whereMonth('valid_card_end', Carbon::now()->month)
                    ->whereYear('valid_card_end', Carbon::now()->year)
                    ->get();
        $license_expiration_tables = LegalDocumentChurch::whereMonth('exp_date', Carbon::now()->month)
                    ->whereYear('exp_date', Carbon::now()->year)
                    ->join('legal_documents','legal_document_churches.legal_document_id','legal_documents.id')
                    ->select('documents','exp_date')
                    ->get();
        $inactive_church_tables = Church::where('church_status', 'Non Active')
                    ->get();
        $inactive_pastor_tables = Personel::whereNotIn('acc_status_id', [1])
                    ->get();
        $new_pastor_tables = Personel::whereMonth('valid_card_start', Carbon::now()->month)
                    ->whereYear('valid_card_start', Carbon::now()->year)
                    ->get();
        $new_church_tables = Church::whereMonth('founded_on', Carbon::now()->month)
                    ->whereYear('founded_on', Carbon::now()->year)
                    ->get();

        $data['church_count'] = $church_count;
        $data['country_count'] = $country_tables->count();
        $data['personel_count'] = $personel_count->count();
        $data['today_birthday'] = $today_birthday->count();

        $data['type_tables'] = $type_tables;
        $data['country_tables'] = $country_tables;
        $data['personel_tables'] = $personel_tables;
        $data['rcdpw_tables'] = $rcdpw_tables;
        $data['personel_vip_tables'] = $personel_vip_tables;
        $data['ministry_role_tables'] = $ministry_role_tables;
        $data['pastors_birthday_tables'] = $pastors_birthday_tables;
        $data['pastors_anniversary_tables'] = $pastors_anniversary_tables;
        $data['id_card_expiration_tables'] = $id_card_expiration_tables;
        $data['license_expiration_tables'] = $license_expiration_tables;
        $data['inactive_church_tables'] = $inactive_church_tables;
        $data['inactive_pastor_tables'] = $inactive_pastor_tables;
        $data['new_pastor_tables'] = $new_pastor_tables;
        $data['new_church_tables'] = $new_church_tables;

        return view('vendor.backpack.base.dashboard',$data);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DashboardRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
