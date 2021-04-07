@extends(backpack_view('blank'))

@php
	$entry = $crud->getCurrentEntry();
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid d-print-none">
    	<a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
		<h2>
	        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection

@section('content')
<!-- <div class="row"> -->
	<!-- <div class="{{ $crud->getShowContentClass() }}"> -->

	<div class ="row">
		<div class="col-md-12">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Biodata
  				</div>
				<div class="text-center">
					<img width="150px" style="margin:15px ; border-radius: 50%" src="{{str_replace('public', '', URL::to('/'))}}{{ str_replace('storage', 'storage/app/public', $entry->image) }}" alt="">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<table class = "table table-striped">
							<tr>
									<td>Status</td>
									<td> : {{ $entry->accountstatus->acc_status }}</td>
								</tr>
								<tr>
									<td>Regional Council</td>
									<td> :  {{ $entry->rc_dpw->rc_dpw_name }}</td>
								</tr>
								<tr>
									<td>Title</td>
									<td> :  {{ $entry->title->short_desc }}</td>
								</tr>
								<tr>
									<td>First Name</td>
									<td> :  {{ $entry->first_name }}</td>
								</tr>
								<tr>
									<td>Last Name</td>
									<td> :  {{ $entry->last_name }}</td>
								</tr>
								<tr>
									<td>Gender</td>
									<td> :  {{ $entry->gender }}</td>
								</tr>
								<tr>
									<td>Date of Birth</td>
									<td> :  {{ $entry->date_of_birth }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class = "table table-striped">
							<tr>
									<td>Marital Status</td>
									<td> :  {{ $entry->marital_status }}</td>
								</tr>
								<tr>
									<td>Spouse Name</td>
									<td> :  {{ $entry->spouse_name }}</td>
								</tr>
								<tr>
									<td>Spouse Date of Birth</td>
									<td> :  {{ $entry->spouse_date_of_birth }}</td>
								</tr>
								<tr>
									<td>Anniversary</td>
									<td> :  {{ $entry->anniversary }}</td>
								</tr>
								<tr>
									<td>Children's Name</td>
									<td> :  {{ $entry->child_name }}</td>
								</tr>
								<tr>
									<td>Ministry Background</td>
									<td> :  {{ $entry->ministry_background }}</td>
								</tr>
								<tr>
									<td>Career Background</td>
									<td> :  {{ $entry->career_background }}</td>
								</tr>
							</table>
						</div>
					</div>	
				</div>
			</div>
		</div>
    	<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Contact Information
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
							<tr>
									<td>Street Address</td>
									<td> :  {{ $entry->street_address }}</td>
								</tr>
								<tr>
									<td>City</td>
									<td> :  {{ $entry->city }}</td>
								</tr>
								<tr>
									<td>Province</td>
									<td> :  {{ $entry->province }}</td>
								</tr>
								<tr>
									<td>Postal Code</td>
									<td> :  {{ $entry->postal_code }}</td>
								</tr>
								<tr>
									<td>Country</td>
									<td> :  {{ $entry->country->country_name }}</td>
								</tr>
								<tr>
									<td>Email</td>
									<td> :  {{ $entry->first_email }}</td>
								</tr>
								<tr>
									<td>Email 2</td>
									<td> :  {{ $entry->second_email }}</td>
								</tr>
								<tr>
									<td>Phone</td>
									<td> :  {{ $entry->phone }}</td>
								</tr>
								<tr>
									<td>Fax</td>
									<td> :  {{ $entry->fax }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Licensing Information
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
								<tr>
									<td>First Licensed On</td>
									<td> :  {{ $entry->first_lisenced_on }}</td>
								</tr>
								<tr>
									<td>Card</td>
									<td> :  {{ $entry->card }}</td>
								</tr>
								<tr>
									<td>Valid Card Start</td>
									<td> :  {{ $entry->valid_card_start }}</td>
								</tr>
								<tr>
									<td>Valid Card End</td>
									<td> :  {{ $entry->valid_card_end }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Appointment History
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
								<tr>
									<td>Title</td>
								</tr>
								<tr>
									<td>Date</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Special Role
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
								<tr>
									<td>No</td>
								</tr>
								<tr>
									<td>Role</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Related Entity
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-6">
							<table class = "table table-striped">
								<tr>
									<td>No</td>
								</tr>
								<tr>
									<td>Entity</td>
								</tr>
								<tr>
									<td>Address</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class = "table table-striped">
								<tr>
									<td>Office Address</td>
								</tr>
								<tr>
									<td>Phone</td>
								</tr>
								<tr>
									<td>Role</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Education Background
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
								<tr>
									<td>Degree</td>
								</tr>
								<tr>
									<td>Type</td>
								</tr>
								<tr>
									<td>Concentration</td>
								</tr>
								<tr>
									<td>School</td>
								</tr>
								<tr>
									<td>Year</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  		Status History
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table class = "table table-striped">
								<tr>
									<td>Status</td>
								</tr>
								<tr>
									<td>Reason</td>
								</tr>
								<tr>
									<td>Date</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Default box -->
	  <div class="">
	  	@if ($crud->model->translationEnabled())
			<div class="row">
				<div class="col-md-12 mb-2">
					<!-- Change translation button group -->
					<div class="btn-group float-right">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('locale')?request()->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						@foreach ($crud->model->getAvailableLocales() as $key => $locale)
							<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?locale={{ $key }}">{{ $locale }}</a>
						@endforeach
					</ul>
					</div>
				</div>
			</div>
	    @endif
	    <div class="card no-padding no-border">
			<table class="table table-striped mb-0">
		        <tbody>
		        @foreach ($crud->columns() as $column)
		            <tr>
		                <td>
		                    <strong>{!! $column['label'] !!}:</strong>
		                </td>
                        <td>
							@if (!isset($column['type']))
		                      @include('crud::columns.text')
		                    @else
		                      @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
		                        @include('vendor.backpack.crud.columns.'.$column['type'])
		                      @else
		                        @if(view()->exists('crud::columns.'.$column['type']))
		                          @include('crud::columns.'.$column['type'])
		                        @else
		                          @include('crud::columns.text')
		                        @endif
		                      @endif
		                    @endif
                        </td>
		            </tr>
		        @endforeach
				@if ($crud->buttons()->where('stack', 'line')->count())
					<tr>
						<td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
						<td>
							@include('crud::inc.button_stack', ['stack' => 'line'])
						</td>
					</tr>
				@endif
		        </tbody>
			</table>
	    </div><!-- /.box-body -->
	  </div><!-- /.box -->

	</div>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}">
@endsection

@section('after_scripts')
	<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
	<script src="{{ asset('packages/backpack/crud/js/show.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
@endsection
