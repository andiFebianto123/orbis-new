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



Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::get('profile-biodata/{id}', 'Api\DetailPersonelApiController@biodata');
   Route::get('profile-contact-information/{id}', 'Api\DetailPersonelApiController@contactInformation');
   Route::get('profile-licensing-information/{id}', 'Api\DetailPersonelApiController@licensingInformation');
   Route::get('profile-appointment-histories/{id}', 'Api\DetailPersonelApiController@appointmentHistories');
   Route::get('profile-special-roles/{id}', 'Api\DetailPersonelApiController@specialRoles');
   Route::get('profile-related-entities/{id}', 'Api\DetailPersonelApiController@relatedEntities');
   Route::get('profile-education-backgrounds/{id}', 'Api\DetailPersonelApiController@educationBackgrounds');
   Route::get('profile-child-names/{id}', 'Api\DetailPersonelApiController@childNames');
   Route::get('profile-ministry-backgrounds/{id}', 'Api\DetailPersonelApiController@ministryBackgrounds');
   Route::get('profile-career-backgrounds/{id}', 'Api\DetailPersonelApiController@careerBackgrounds');
   Route::get('profile-status-histories/{id}', 'Api\DetailPersonelApiController@statusHistories');
   Route::get('profile-churches/{id}', 'Api\DetailPersonelApiController@churches');
   Route::post('profile-update', 'Api\DetailPersonelApiController@update');

   Route::get('church-coordinator/{id}', 'Api\DetailChurchApiController@coordinator');
   Route::get('church-leadership/{id}', 'Api\DetailChurchApiController@leadership');
   Route::get('church-information/{id}', 'Api\DetailChurchApiController@information');
   Route::get('church-status-history/{id}', 'Api\DetailChurchApiController@statusHistory');
   Route::get('church-related-entity/{id}', 'Api\DetailChurchApiController@relatedEntity');
   
   Route::get('master-title', 'Api\DataMasterApiController@title');
   Route::get('master-regional-council', 'Api\DataMasterApiController@regionalCouncil');
   Route::get('master-country', 'Api\DataMasterApiController@country');
   Route::get('master-marital-status', 'Api\DataMasterApiController@maritalStatus');
  
   Route::post('logout', 'Api\AuthApiController@logout');
   Route::post('logout-all', 'Api\AuthApiController@logoutAll');
});

