<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\AuthApiController@login');


Route::get('church-list', 'Api\DetailChurchApiController@list');
Route::get('cek-log', 'Api\DetailChurchApiController@cekLog');

Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::get('profile-biodata/{id}', 'Api\DetailPersonelApiController@biodata');
   Route::get('profile-contact-information/{id}', 'Api\DetailPersonelApiController@contactInformation');
   Route::get('profile-licensing-information/{id}', 'Api\DetailPersonelApiController@licensingInformation');
   Route::get('profile-appointment-histories/{id}', 'Api\DetailPersonelApiController@appointmentHistories');
   Route::get('profile-special-roles/{id}', 'Api\DetailPersonelApiController@specialRoles');
   Route::get('profile-related-entities/{id}', 'Api\DetailPersonelApiController@relatedEntities');
   Route::get('profile-child-names/{id}', 'Api\DetailPersonelApiController@childNames');
   Route::get('profile-status-histories/{id}', 'Api\DetailPersonelApiController@statusHistories');
   Route::get('profile-churches/{id}', 'Api\DetailPersonelApiController@churches');
   Route::post('profile-update', 'Api\DetailPersonelApiController@update');

   Route::get('church-coordinator/{id}', 'Api\DetailChurchApiController@coordinator');
   Route::get('church-leadership/{id}', 'Api\DetailChurchApiController@leadership');
   Route::get('church-information/{id}', 'Api\DetailChurchApiController@information');
   Route::get('church-status-history/{id}', 'Api\DetailChurchApiController@statusHistory');
   Route::get('church-related-entity/{id}', 'Api\DetailChurchApiController@relatedEntity');
   Route::post('church-update', 'Api\DetailChurchApiController@update');

   Route::get('profile-education-backgrounds/{id}', 'Api\EducationBackgroundApiController@list');
   Route::post('education-backgrounds-update', 'Api\EducationBackgroundApiController@update');
   Route::post('education-backgrounds-create', 'Api\EducationBackgroundApiController@create');
   Route::post('education-backgrounds-delete', 'Api\EducationBackgroundApiController@delete');

   Route::get('profile-ministry-backgrounds/{id}', 'Api\MinistryBackgroundApiController@list');
   Route::post('ministry-backgrounds-update', 'Api\MinistryBackgroundApiController@update');
   Route::post('ministry-backgrounds-create', 'Api\MinistryBackgroundApiController@create');
   Route::post('ministry-backgrounds-delete', 'Api\MinistryBackgroundApiController@delete');

   Route::get('profile-child-names/{id}', 'Api\ChildNameApiController@list');
   Route::post('child-names-update', 'Api\ChildNameApiController@update');
   Route::post('child-names-create', 'Api\ChildNameApiController@create');
   Route::post('child-names-delete', 'Api\ChildNameApiController@delete');

   Route::get('profile-career-backgrounds/{id}', 'Api\CareerBackgroundApiController@list');
   Route::post('career-backgrounds-update', 'Api\CareerBackgroundApiController@update');
   Route::post('career-backgrounds-create', 'Api\CareerBackgroundApiController@create');
   Route::post('career-backgrounds-delete', 'Api\CareerBackgroundApiController@delete');

   Route::get('structure-churches-show/{id}', 'Api\LeadershipStructureApiController@show');
   Route::post('structure-churches-update', 'Api\LeadershipStructureApiController@update');
   Route::post('structure-churches-create', 'Api\LeadershipStructureApiController@create');
   Route::post('structure-churches-delete', 'Api\LeadershipStructureApiController@delete');

   Route::get('master-title', 'Api\DataMasterApiController@title');
   Route::get('master-regional-council', 'Api\DataMasterApiController@regionalCouncil');
   Route::get('master-country', 'Api\DataMasterApiController@country');
   Route::get('master-marital-status', 'Api\DataMasterApiController@maritalStatus');
   Route::get('master-gender', 'Api\DataMasterApiController@gender');
   Route::get('master-church', 'Api\DataMasterApiController@church');
   Route::get('master-ministry-role', 'Api\DataMasterApiController@ministryRole');
   
   Route::get('log-hub-list', 'Api\LogHubApiController@list');

   Route::post('logout', 'Api\AuthApiController@logout');
   Route::post('logout-all', 'Api\AuthApiController@logoutAll');
});

