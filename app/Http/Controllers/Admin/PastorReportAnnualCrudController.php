<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PastorReportAnnualRequest;
use App\Models\RcDpwList;
use App\Models\CountryList;
use App\Models\TitleList;
use App\Models\PastorAnnualDesignerView;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PastorReportAnnualCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

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
                    'label' => 'Pastor',
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
                    'label' => 'Current Certiicate Number',
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
        else{
            $this->crud->denyAccess('list');
        }
    }

    public function setupListReport()
    {
        $detailYear = $this->getCurrentYear();
        $crudModel = $this->crud->typeReport == "annual" ? \App\Models\PastorAnnualView::class : \App\Models\PastorAnnualDesignerView::class;
        $crudRoute = $this->crud->typeReport == "annual" ? 
        config('backpack.base.route_prefix') . '/pastor-annual-report' : 
        ( $this->crud->typeReport == "designer" ? config('backpack.base.route_prefix') . '/pastor-report-designer' : 
        config('backpack.base.route_prefix') . '/pastor-annual-report/' . $detailYear . '/detail');
        $entityName = $this->crud->typeReport == "annual" ? "Pastor Annual Report" :  ( $this->crud->typeReport == "designer" ? "Pastor Report Designer" :
        "Church List " . $detailYear);
        $this->crud->entityName = $entityName;
        $this->crud->entityNameAnnual = "Pastor Annual Report";
        $this->crud->routeAnnual = config('backpack.base.route_prefix') . '/pastor-annual-report';
        $this->crud->routeDesigner = config('backpack.base.route_prefix') . '/pastor-report-designer';

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
                $this->crud->card = PastorAnnualDesignerView::select('id', 'card')->get();
                $this->crud->country = CountryList::select('id', 'country_name')->get();
                $this->crud->pastorStatus = PastorAnnualDesignerView::select('status')->get();
                $this->crud->title = TitleList::select('id', 'short_desc')->get();
            }
            if ($this->crud->getRequest()->filled('rc_dpw_id')) {
                try{
                    $this->crud->addClause('where', 'rc_dpw_name', $this->crud->getRequest()->rc_dpw_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('title_id')) {
                try{
                    $this->crud->addClause('where', 'short_desc', $this->crud->getRequest()->church_type_id);
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
            if ($this->crud->getRequest()->filled('pastor_status_id')) {
                try{
                    $this->crud->addClause('where', 'status', $this->crud->getRequest()->pastor_status_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('card_id')) {
                try{
                    $this->crud->addClause('where', 'card', $this->crud->getRequest()->card_id);
                }
                catch(Exception $e){
    
                }
            }
            if ($this->crud->getRequest()->filled('filter_type')) {
                try{
                    if($this->crud->getRequest()->filter_type == 'd90'){
                        $realDateNow = Carbon::now();
                        $maximumDateValid = $realDateNow->copy()->subDays(90);
                        $this->crud->addClause('whereDate', 'valid_card_end', '<=', $realDateNow->toDateString());
                        $this->crud->addClause('whereDate', 'valid_card_end', '>=', $maximumDateValid->toDateString());
                    }                    
                }
                catch(Exception $e){
    
                }
            }
            $this->crud->viewBeforeContent = ['annualreport.report_designer_pastor_panel'];
        }
        
    }

    private function getCurrentType()
    {
        $route = explode('/',Route::current()->uri);

        return Route::current()->parameter('year') != null ? 'detail' : (preg_match('/pastor-report-designer/', $route[1]) ? 'designer' : 'annual');
    }

    private function getCurrentYear()
    {
        return Route::current()->parameter('year');
    }
}
