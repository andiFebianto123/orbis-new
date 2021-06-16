<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChurchAnnualReportRequest;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\ChurchAnnualDesignerView;
use App\Models\RcDpwList;
use App\Models\ChurchEntityType;
use App\Models\CountryList;
use App\Exports\ExportAnnualReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ChurchAnnualReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    protected $listColumns;

    public function setup()
    {
        $this->crud->typeReport = $this->getCurrentType();
        $this->crud->disableResponsiveTable();
        $this->crud->disablePersistentTable();
        $this->crud->denyAccess(['create', 'update', 'show']);
        $this->setupListReport();
    }
    
    protected function setupListOperation()
    {
        if($this->crud->typeReport == 'annual'){
            CRUD::addColumns([
                [
                    'label' => 'Year',
                    'type' => 'text',
                    'name' => 'year'
                ],
                [
                    'label' => 'Churches',
                    'type' => 'text',
                    'name' => 'total'
                ]
            ]);
        }
        else if($this->crud->typeReport == 'detail' || $this->crud->typeReport == 'designer'){
            CRUD::addColumns([
                [
                    'label' => 'RC / DPW',
                    'type' => 'text',
                    'name' => 'rc_dpw_name'
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
            $this->crud->denyAccess('list');
        }
        
    }

    public function setupListReport()
    {
        $detailYear = $this->getCurrentYear();
        $crudModel = $this->crud->typeReport == "annual" ? \App\Models\ChurchAnnualView::class : \App\Models\ChurchAnnualDesignerView::class;
        $crudRoute = $this->crud->typeReport == "annual" ? 
        config('backpack.base.route_prefix') . '/church-annual-report' : 
        ( $this->crud->typeReport == "designer" ? config('backpack.base.route_prefix') . '/church-report-designer' : 
        config('backpack.base.route_prefix') . '/church-annual-report/' . $detailYear . '/detail');
        $entityName = $this->crud->typeReport == "annual" ? "Church Annual Report" :  ( $this->crud->typeReport == "designer" ? "Church Report Designer" :
        "Church List " . $detailYear);
        $this->crud->entityName = $entityName;
        $this->crud->entityNameAnnual = "Church Annual Report";
        $this->crud->routeAnnual = config('backpack.base.route_prefix') . '/church-annual-report';
        $this->crud->routeDesigner = config('backpack.base.route_prefix') . '/church-report-designer';
        $this->crud->viewAfterContent = ['export_report'];
        $this->crud->routeExport =  $this->crud->typeReport == "annual" ?  '/church-annual-report' : ( $this->crud->typeReport == "designer" ? '/church-report-designer' : 
        '/church-annual-report/' . $detailYear . '/detail');

        CRUD::setModel($crudModel);
        CRUD::setRoute($crudRoute);
        CRUD::setEntityNameStrings($entityName, $entityName);

        $this->crud->addButtonFromModelFunction('top', 'exportButton', 'ExportExcelButton');
        if($this->crud->typeReport == "annual"){
            $this->crud->orderBy("year");
            $this->crud->addButtonFromModelFunction('line', 'detailButton', 'DetailButton');
        }
        else if($this->crud->typeReport == "detail"){
            $this->crud->addClause('year', $detailYear);
        }
        else{
            if(! request()->ajax()){
                $this->crud->rc_dpw = RcDpwList::select('id', 'rc_dpw_name')->get();
                $this->crud->churchType = ChurchEntityType::select('id', 'entities_type')->get();
                $this->crud->country = CountryList::select('id', 'country_name')->get();
                $this->crud->churchStatus = ChurchAnnualDesignerView::select('status')->groupBy('status')->get();
            }
            if ($this->crud->getRequest()->filled('rc_dpw_id')) {
                try{
                    $this->crud->addClause('where', 'rc_dpw_name', $this->crud->getRequest()->rc_dpw_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('church_type_id')) {
                try{
                    $this->crud->addClause('where', 'entities_type', $this->crud->getRequest()->church_type_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('country_id')) {
                try{
                    $this->crud->addClause('where', 'country_name', $this->crud->getRequest()->country_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('church_status_id')) {
                try{
                    $this->crud->addClause('where', 'status', $this->crud->getRequest()->church_status_id);
                }
                catch(Exception $e){
    
                }
            }
            $this->crud->viewBeforeContent = ['annualreport.report_designer_panel'];
        }
        
    }

    private function getCurrentType()
    {
        $route = explode('/',Route::current()->uri);

        return Route::current()->parameter('year') != null ? 'detail' : (preg_match('/church-report-designer/', $route[1]) ? 'designer' : 'annual');
    }

    private function getCurrentYear()
    {
        return Route::current()->parameter('year');
    }

    public function exportReport(Request $request)
    {
        $visibleColumn = $request->visible_column;
        $type = 'church_' . $this->crud->typeReport;
        $year = $this->getCurrentYear() ?? 0;
        $fileName = $this->crud->typeReport == 'annual' ? 'Church Annual Report' : ($this->crud->typeReport == 'detail' ? 'Church List ' . $year : 'Church Report');
        $this->setupListOperation();
        $columnList = CRUD::columns();
        $realVisibleColumn = [];
        $index = 0;
        $filterBy = [];
        if($this->crud->typeReport == 'designer')
        {
            if($request->rc_dpw_id != "null"){
                $filterBy['rc_dpw_name'] = $request->rc_dpw_id;
            }
            if($request->church_type_id != "null"){
                $filterBy['entities_type'] = $request->church_type_id;
            }
            if($request->country_id != "null"){
                $filterBy['country_name'] = $request->country_id;
            }
            if($request->church_status_id != "null"){
                $filterBy['status'] = $request->church_status_id;
            }
        }
        
        foreach($columnList as $indexColumn => $columnData){
            if($this->crud->typeReport  != 'designer' || (isset($visibleColumn) && in_array($index, $visibleColumn))){
                $realVisibleColumn[$indexColumn] = $columnData['label'];
            }
            $index++;
        }
        return Excel::download(new ExportAnnualReport($type, $realVisibleColumn, $year, $filterBy), $fileName . '.xlsx');
    }
}
