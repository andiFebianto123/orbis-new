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
									<td> :  {{ $entry->email }}</td>
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
  							<a href ="{{url('admin/appointment_history/create?personel_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Appointment</a>
							<table id ="tableAppointmentHistory" class = "table table-striped">
								<thead>
									<tr >
										<th>Title</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->appointment_history as $key => $ah)
										<tr>
											<td>{{$ah->title_appointment}}</td>
											<td>{{$ah->date_appointment}}</td> 
											<td>
											<a href="{{url('admin/appointment_history/'.$ah->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/appointment_history/'.$ah->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
										</tr>
									@endforeach
								</tbody>
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
						<a href ="{{url('admin/specialrolepersonel/create?personel_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Special Role</a>
							<table id ="tableSpecialRolePersonel" class = "table table-striped">
								<thead>
									<tr >
										<th>No</th>
										<th>Special Role</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->special_role_personel as $key => $srp)
										<tr>
											<td>{{$srp->id}}</td>
											<td>{{$srp->special_role_personel->special_role_name}}</td>
											<td>
											<a href="{{url('admin/specialrolepersonel/'.$srp->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/specialrolepersonel/'.$srp->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
										</tr>
									@endforeach
								</tbody>
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
						<div class="col-md-12">
						<a href ="{{url('admin/relatedentity/create?personel_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Related Entity</a>
							<table id ="tableRelatedEntity" class = "table table-striped">
								<thead>
									<tr >
										<th>No</th>
										<th>Entity</th>
										<th>Address</th>
										<th>Office Address</th>
										<th>Phone</th>
										<th>Role</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->related_entity as $key => $re)
										<tr>
											<td>{{$re->id}}</td>
											<td>{{$re->entity}}</td>
											<td>{{$re->address_entity}}</td>
											<td>{{$re->office_address_entity}}</td>
											<td>{{$re->phone}}</td>
											<td>{{$re->role}}</td>
											<td>
											<a href="{{url('admin/relatedentity/'.$re->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/relatedentity/'.$re->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
										</tr>
									@endforeach
								</tbody>
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
						<a href ="{{url('admin/educationbackground/create?personel_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Education</a>
							<table id ="tableEducationBackground" class = "table table-striped">
								<thead>
									<tr >
										<th>Degree</th>
										<th>Type</th>
										<th>Concentration</th>
										<th>School</th>
										<th>Year</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->education_background as $key => $eb)
										<tr>
											<td>{{$eb->degree}}</td>
											<td>{{$eb->type_education}}</td>
											<td>{{$eb->concentration_education}}</td>
											<td>{{$eb->school}}</td>
											<td>{{$eb->year}}</td>
											<td>
											<a href="{{url('admin/educationbackground/'.$eb->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/educationbackground/'.$eb->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
										</tr>
									@endforeach
								</tbody>
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
						<a href ="{{url('admin/statushistory/create?personel_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Status</a>
							<table id ="tableStatusHistory" class = "table table-striped">
								<thead>
									<tr >
										<th>Status</th>
										<th>Reason</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->status_history as $key => $sh)
										<tr>
											<td>{{$sh->status}}</td>
											<td>{{$sh->reason}}</td>
											<td>{{$sh->date_status}}</td>
											<td>
											<a href="{{url('admin/statushistory/'.$sh->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/statushistory/'.$sh->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
										</tr>
									@endforeach
								</tbody>
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

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

@endsection

@section('after_scripts')
	<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
	<script src="{{ asset('packages/backpack/crud/js/show.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	
	<script>
		$(document).ready(function() {
		$('#tableAppointmentHistory').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableRelatedEntity').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableEducationBackground').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableStatusHistory').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableSpecialRolePersonel').DataTable();
		} );
	</script>

	<script>
		if (typeof deleteEntry != 'function') {
		$("[data-button-type=delete]").unbind('click');

		function deleteEntry(button) {
			// ask for confirmation before deleting an item
			// e.preventDefault();
			var route = $(button).attr('data-route');

			swal({
			title: "{!! trans('backpack::base.warning') !!}",
			text: "{!! trans('backpack::crud.delete_confirm') !!}",
			icon: "warning",
			buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.delete') !!}"],
			dangerMode: true,
			}).then((value) => {
				if (value) {
					$.ajax({
					url: route,
					type: 'DELETE',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					success: function(result) {
						if (result == 1) {
							// Redraw the table
							if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
								// Move to previous page in case of deleting the only item in table
								if(crud.table.rows().count() === 1) {
									crud.table.page("previous");
								}

								crud.table.draw(false);
							}

							// Show a success notification bubble
							new Noty({
								type: "success",
								text: "{!! '<strong>'.trans('backpack::crud.delete_confirmation_title').'</strong><br>'.trans('backpack::crud.delete_confirmation_message') !!}"
							}).show();
							location.reload();

							// Hide the modal, if any
							$('.modal').modal('hide');
						} else {
							// if the result is an array, it means 
							// we have notification bubbles to show
							if (result instanceof Object) {
								// trigger one or more bubble notifications 
								Object.entries(result).forEach(function(entry, index) {
								var type = entry[0];
								entry[1].forEach(function(message, i) {
									new Noty({
										type: type,
										text: message
									}).show();
								});
								});
							} else {// Show an error alert
								swal({
									title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
									text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
									icon: "error",
									timer: 4000,
									buttons: false,
								});
							}			          	  
						}
					},
					error: function(result) {
						// Show an alert with the result
						swal({
							title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
							text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
							icon: "error",
							timer: 4000,
							buttons: false,
						});
					}
				});
				}
			});

		}
		}

		// make it so that the function above is run after each DataTable draw event
		// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
	</script>

@endsection
