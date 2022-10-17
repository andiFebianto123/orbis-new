<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuickReportRequest;
use Carbon\Carbon;
use App\Exports\ExportAnnualReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Church;
use App\Models\Personel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuickReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuickReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->setupList();
        $this->crud->viewBeforeContent=['quickreport.quick_report_panel'];
        CRUD::setRoute(config('backpack.base.route_prefix') . '/quick-report');
        CRUD::setEntityNameStrings('Quick Reports', 'Quick Reports');
        if($this->crud->requestQuickReport == 'new_church' || $this->crud->requestQuickReport == 'new_pastor'){
            $this->crud->addClause('year', Carbon::now()->year);
        }else if($this->crud->requestQuickReport == 'recent_church' ){
            $this->crud->addClause('year', Carbon::now()->year);
            $this->crud->addClause("where", 'status', 'Non-active');
        }else if($this->crud->requestQuickReport == 'recent_pastor'){
            $this->crud->addClause('year', Carbon::now()->year);
            $this->crud->addClause("where", 'status', 'Inactive');
        }
    }

    protected function setupListOperation()
    { 
        if(preg_match('/church+/',$this->crud->requestQuickReport)){
            CRUD::addColumns([
                // [
                //     'label' => 'RC / DPW',
                //     'type' => 'text',
                //     'name' => 'rc_dpw_name'
                // ],
                [
                    'label' => 'RC / DPW',
                    'type' => 'text',
                    'name' => 'rc_dpw_name',
                ],
                [
                    'label' => 'Church Name',
                    'type' => 'text',
                    'name' => 'church_name',
                ],
                [
                    'label' => 'Church Type',
                    'type' => 'text',
                    'name' => 'entities_type'
                ],
                [
                    'label' => 'Lead Pastor Name',
                    'type' => 'text',
                    'name' => 'lead_pastor_name'
                ],
                [
                    'label' => 'Contact Person',
                    'type' => 'text',
                    'name' => 'contact_person'
                ],
                [
                    'label' => 'Church Address',
                    'type' => 'text',
                    'name' => 'church_address'
                ],
                [
                    'label' => 'Office Address',
                    'type' => 'text',
                    'name' => 'office_address'
                ],
                [
                    'label' => 'City',
                    'type' => 'text',
                    'name' => 'city'
                ],
                [
                    'label' => 'Province / State',
                    'type' => 'text',
                    'name' => 'province'
                ],
                [
                    'label' => 'Postal Code',
                    'type' => 'text',
                    'name' => 'postal_code'
                ],
                [
                    'label' => 'Country',
                    'type' => 'text',
                    'name' => 'country_name'
                ],
                [
                    'label' => 'Phone',
                    'type' => 'text',
                    'name' => 'phone'
                ],
                [
                    'label' => 'Fax',
                    'type' => 'text',
                    'name' => 'fax'
                ],
                [
                    'label' => 'Email',
                    'type' => 'text',
                    'name' => 'first_email'
                ],
                [
                    'label' => 'Church Status',
                    'type' => 'closure',
                    'name' => 'status',
                    'function' => function($entries){
                        return $entries->status != null ? $entries->status : '-';
                    }
                ],
                [
                    'label' => 'Founded On',
                    'type' => 'text',
                    'name' => 'founded_on'
                ],
                [
                    'label' => 'Service Time Church',
                    'type' => 'text',
                    'name' => 'service_time_church'
                ],
                [
                    'label' => 'Notes',
                    'type' => 'text',
                    'name' => 'notes'
                ]
            ]);
        }
        else{
            CRUD::addColumns([
                // [
                //     'label' => 'RC / DPW',
                //     'type' => 'text',
                //     'name' => 'rc_dpw_name'
                // ],
                [
                    'label' => 'RC / DPW',
                    'type' => 'closure',
                    'name' => 'rc_dpw_name',
                    'function' => function($entries){
                        $personel = Personel::where('id', $entries->id)->first();
                        if($personel != null){
                            return $personel->pivod_rcdpw->implode('rc_dpw_name', ', ');
                        }
                        return '-';
                    },
                    'searchLogic' => function ($query, $column, $searchTerm) {
                        $query->orWhereRaw("EXISTS (
                                SELECT 1 FROM personels_rcdpw 
                                INNER JOIN rc_dpwlists ON rc_dpwlists.id = personels_rcdpw.rc_dpwlists_id
                                WHERE personels_rcdpw.personels_id = pastor_annual_designer_views.id AND rc_dpwlists.rc_dpw_name LIKE '%{$searchTerm}%'
                        )");
                    },
                ],
                [
                    'label' => 'Title',
                    'type' => 'text',
                    'name' => 'short_desc',
                ],
                [
                    'label' => 'First Name',
                    'type' => 'text',
                    'name' => 'first_name'
                ],
                [
                    'label' => 'Last Name',
                    'type' => 'text',
                    'name' => 'last_name'
                ],
                [
                    'label' => 'Gender',
                    'type' => 'text',
                    'name' => 'gender'
                ],
                [
                    'label' => 'Church Name',
                    'type' => 'text',
                    'name' => 'church_name'
                ],
                [
                    'label' => 'Address',
                    'type' => 'text',
                    'name' => 'street_address'
                ],
                [
                    'label' => 'City',
                    'type' => 'text',
                    'name' => 'city'
                ],
                [
                    'label' => 'Province / State',
                    'type' => 'text',
                    'name' => 'province'
                ],
                [
                    'label' => 'Postal Code',
                    'type' => 'text',
                    'name' => 'postal_code'
                ],
                [
                    'label' => 'Country',
                    'type' => 'text',
                    'name' => 'country_name'
                ],
                [
                    'label' => 'Phone',
                    'type' => 'text',
                    'name' => 'phone'
                ],
                [
                    'label' => 'Fax',
                    'type' => 'text',
                    'name' => 'fax'
                ],
                [
                    'label' => 'Email',
                    'type' => 'text',
                    'name' => 'email'
                ],
                [
                    'label' => 'Marital Status',
                    'type' => 'text',
                    'name' => 'marital_status'
                ],
                [
                    'label' => 'Date of Birth',
                    'type' => 'text',
                    'name' => 'date_of_birth'
                ],
                [
                    'label' => 'Spouse Name',
                    'type' => 'text',
                    'name' => 'spouse_name'
                ],
                [
                    'label' => 'Spouse Date of Birth',
                    'type' => 'text',
                    'name' => 'spouse_date_of_birth'
                ],
                [
                    'label' => 'Anniversary',
                    'type' => 'text',
                    'name' => 'anniversary'
                ],
                [
                    'label' => 'Status',
                    'type' => 'closure',
                    'name' => 'status',
                    'function' => function($entries){
                        return $entries->status != null ? $entries->status : '-';
                    }
                ],
                [
                    'label' => 'First Licensed On',
                    'type' => 'text',
                    'name' => 'first_licensed_on'
                ],
                [
                    'label' => 'Card',
                    'type' => 'text',
                    'name' => 'card'
                ],
                [
                    'label' => 'Valid Card Start',
                    'type' => 'text',
                    'name' => 'valid_card_start'
                ],
                [
                    'label' => 'Valid Card End',
                    'type' => 'text',
                    'name' => 'valid_card_end'
                ],
                [
                    'label' => 'Current Certificate Number',
                    'type' => 'text',
                    'name' => 'current_certificate_number'
                ],
                [
                    'label' => 'Notes',
                    'type' => 'text',
                    'name' => 'notes'
                ]
            ]);
        }
    }

    protected function setupList(){
        if($this->crud->getRequest()->filled('report_type')){
            if(preg_match('/church+/', $this->crud->getRequest()->report_type)){
                $modelCrud = \App\Models\ChurchAnnualDesignerView::class;
            }else{
                $modelCrud = \App\Models\PastorAnnualDesignerView::class;
            }
            $this->crud->requestQuickReport = $this->crud->getRequest()->report_type;
            $this->crud->routeExport = '/quick-report';
            $this->crud->viewAfterContent = ['export_report'];
            $this->crud->addButtonFromModelFunction('top', 'exportButton', 'ExportExcelButton');
        }else{
            $modelCrud = \App\Models\ChurchAnnualView::class;
            $this->crud->requestQuickReport = null;
            
        }
        CRUD::setModel($modelCrud);
        $this->crud->quickReport = true;
        $this->crud->type_report =
        [
            'new_church' => 'New Church This Year',
            'new_pastor' => 'New Pastor This Year',
            'recent_church' => 'Recently Inactive Church',
            'recent_pastor' => 'Recently Inactive Pastor',
            'all_church' => 'All Church',
            'all_pastor' => 'All Pastor'
        ];
    }

    public function exportReport(Request $request)
    {
        $type = $request->report_type;
        $this->crud->requestQuickReport = $type;
        $fileName = $this->crud->type_report[$type];
        $this->setupListOperation();
        $columnList = CRUD::columns();
        $realVisibleColumn = [];
        $filterBy = [];
        $index = 0;
        $year = 0;
        foreach($columnList as $indexColumn => $columnData){
            if(!isset($this->crud->typeReport) || $this->crud->typeReport  != 'designer' || (isset($visibleColumn) && in_array($index, $visibleColumn))){
                $realVisibleColumn[$indexColumn] = $columnData['label'];
            }
            $index++;
        }
        
        return Excel::download(new ExportAnnualReport($type, $realVisibleColumn, $year, $filterBy), $fileName . '.xlsx');
    }
}
