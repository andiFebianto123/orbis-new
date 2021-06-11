<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChurchAnnualReportRequest;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ChurchAnnualReportCrudController extends CrudController
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
                    'name' => 'country'
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
                    'label' => 'Church Status',
                    'type' => 'text',
                    'name' => 'status'
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
}
