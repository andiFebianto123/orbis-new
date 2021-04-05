@extends(backpack_view('blank'))

@php
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
<div class="row">
	<!-- <div class="{{ $crud->getShowContentClass() }}"> -->
    <!-- <div class="col-md-8">
		<div class="container col-md-8 mt-4">
			<h1>Information</h1>
		</div> -->
	<div class="card text-left" style="width: 30rem;">
		<div class="card-header text-center ">
			<div class="col-md-12"> 
			Information
			</div>
  		</div>
		<div class="card-body">
			<div class="row">
                <div class="col-md-12"> 
                    <div class="form-group">
                    	<label for="title">Title</label>
                    	<input id="title" type="relationship" attribute="short_desc" value="{{ $personels['title']['short_desc'] }}" class="form-control" disabled />
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input id="first_name" type="text" value="{{ $personels['first_name'] }}" class="form-control" disabled />
                    </div>
			 
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" type="text" value="{{ $personels['last_name'] }}" class="form-control" disabled />
                    </div>

					<div class="form-group">
                    	<label for="accountstatus">Status</label>
                    	<input id="accountstatus" type="relationship" attribute="acc_status" value="{{ $personels['accountstatus'] ['acc_status']  }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                    	<label for="rc_dpw">RC / DPW</label>
                    	<input id="rc_dpw" type="relationship" attribute="rc_dpw_name" value="{{ $personels['rc_dpw'] ['rc_dpw_name'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="first_email">Email 1</label>
                        <input id="first_email" type="email" value="{{ $personels['first_email'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="second_email">Email 2</label>
                        <input id="second_email" type="email" value="{{ $personels['second_email'] }}" class="form-control" disabled />
                    </div>
				
					<div class="form-group">
                        <label for="phone">Phone</label>
                        <input id="phone" type="text" value="{{ $personels['phone'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="street_address">Street Address</label>
                        <input id="street_address" type="text" value="{{ $personels['street_address'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="city">City</label>
                        <input id="city" type="text" value="{{ $personels['city'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="province">Province</label>
                        <input id="province" type="text" value="{{ $personels['province'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input id="postal_code" type="text" value="{{ $personels['postal_code'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="country">Country</label>
                        <input id="country" type="relationship" attribute="country_name" value="{{ $personels['country'] ['country_name'] }}" class="form-control" disabled />
                    </div>
					<div class="form-group">
                        <label for="image">Image</label>
                        <input id="image" type="image" value="{{ $personels['image'] }}" class="form-control" disabled />
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
