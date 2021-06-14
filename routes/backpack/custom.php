<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('accountstatus', 'AccountstatusCrudController');
    Route::crud('rcdpwlist', 'RcDpwListCrudController');
    Route::crud('churchentitytype', 'ChurchEntityTypeCrudController');
    Route::crud('servicetype', 'ServiceTypeCrudController');
    Route::crud('titlelist', 'TitleListCrudController');
    Route::crud('ministryrole', 'MinistryRoleCrudController');
    Route::crud('specialrole', 'SpecialRoleCrudController');
    Route::crud('licensetype', 'LicenseTypeCrudController');
    Route::crud('legaldocument', 'LegalDocumentCrudController');
    Route::crud('countrylist', 'CountryListCrudController');
    Route::crud('personel', 'PersonelCrudController');
    Route::crud('appointment_history', 'Appointment_historyCrudController');
    Route::crud('relatedentity', 'RelatedentityCrudController');
    Route::crud('educationbackground', 'EducationBackgroundCrudController');
    Route::crud('statushistory', 'StatusHistoryCrudController');
    Route::crud('specialrolepersonel', 'SpecialRolePersonelCrudController');
    Route::crud('church', 'ChurchCrudController');
    Route::crud('legaldocumentchurch', 'LegalDocumentChurchCrudController');
    Route::crud('servicetimechurch', 'ServiceTimeChurchCrudController');
    Route::crud('statushistorychurch', 'StatusHistoryChurchCrudController');
    Route::crud('relatedentitychurch', 'RelatedEntityChurchCrudController');
    Route::crud('structurechurch', 'StructureChurchCrudController');
    Route::crud('dashboard', 'DashboardCrudController');
    Route::post('dashboard-upload', 'DashboardCrudController@uploadtest');
    Route::crud('quickreport', 'QuickReportCrudController');
    Route::get('churchreport', 'ChurchAnnualReportController@index');
    Route::get('churchannualreportdetail/{year}', 'ChurchAnnualReportController@detail');
    Route::get('churchreportdesigner', 'ChurchAnnualReportController@reportdesigner');
    Route::get('pastorreport', 'PastorAnnualReportController@index');
    Route::get('pastorannualreportdetail/{year}', 'PastorAnnualReportController@detail');
    Route::get('pastorreportdesigner', 'PastorAnnualReportController@reportdesigner');
    Route::get('newchurchreport', 'QuickReportController@newchurch');
    Route::get('newpastorreport', 'QuickReportController@newpastor');
    Route::get('inactivechurch', 'QuickReportController@inactivechurch');
    Route::get('inactivepastor', 'QuickReportController@inactivepastor');
    Route::get('allchurchreport', 'QuickReportController@allchurch');
    Route::get('allpastorreport', 'QuickReportController@allpastor');

    Route::get('toolsupload', 'ToolsUploadController@index');
    Route::get('import-church', 'ToolsUploadController@importchurch');
    Route::post('church-upload', 'ToolsUploadController@uploadchurch');
    Route::get('import-personel', 'ToolsUploadController@importpersonel');
    Route::post('personel-upload', 'ToolsUploadController@uploadpersonel');

    Route::get('import-country', 'ToolsUploadController@importcountry');
    Route::post('country-upload', 'CountryListCrudController@uploadcountry');

    Route::get('import-rcdpw', 'ToolsUploadController@importrcdpw');
    Route::post('rcdpw-upload', 'RcDpwListCrudController@uploadrcdpw');
    
    Route::crud('childnamepastors', 'ChildNamePastorsCrudController');
    Route::crud('ministrybackgroundpastor', 'MinistryBackgroundPastorCrudController');
    Route::crud('careerbackgroundpastors', 'CareerBackgroundPastorsCrudController');
    Route::crud('church-annual-report', 'ChurchAnnualReportCrudController');
    Route::prefix('church-annual-report/{year}')->group(function(){
        Route::crud('detail', 'ChurchAnnualReportCrudController');
    });
    Route::crud('church-report-designer', 'ChurchAnnualReportCrudController');
    Route::crud('pastor-annual-report', 'PastorReportAnnualCrudController');
    Route::prefix('pastor-annual-report/{year}')->group(function(){
        Route::crud('detail', 'PastorReportAnnualCrudController');
    });
    Route::crud('pastor-report-designer', 'PastorReportAnnualCrudController');
}); // this should be the absolute last line of this file