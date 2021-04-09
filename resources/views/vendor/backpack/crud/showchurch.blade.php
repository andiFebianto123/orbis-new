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
			  		Information
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
									<td> : {{ $entry->church_status }}</td>
								</tr>
								<tr>
									<td>Founded On</td>
									<td> : {{ $entry->founded_on }}</td>
								</tr>
								<tr>
									<td>Church ID</td>
									<td> : {{ $entry->church_id }}</td>
								</tr>
								<tr>
									<td>Type</td>
									<td> :  {{ $entry->church_type->entities_type }}</td>
								</tr>
								<tr>
									<td>RC / DPW</td>
									<td> :  {{ $entry->rc_dpw->rc_dpw_name }}</td>
								</tr>
								<tr>
									<td>Church Name</td>
									<td> :  {{ $entry->church_name }}</td>
								</tr>
								<tr>
									<td>Contact Person</td>
									<td> :  {{ $entry->contact_person }}</td>
								</tr>
								<tr>
									<td>Building Name</td>
									<td> :  {{ $entry->building_name }}</td>
								</tr>
								<tr>
									<td>Church Address</td>
									<td> :  {{ $entry->church_address }}</td>
								</tr>
								<tr>
									<td>Office Address</td>
									<td> :  {{ $entry->office_address }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class = "table table-striped">
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
								<tr>
									<td>Website</td>
									<td> :  {{ $entry->website }}</td>
								</tr>
								<tr>
									<td>Map Url</td>
									<td> :  {{ $entry->map_url }}</td>
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
			  		Legal Document For Church
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
  							<a href ="{{url('admin/legaldocumentchurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Document</a>
							<table id ="tableLegalDocument" class = "table table-striped">
								<thead>
									<tr >
										<th>Document</th>
										<th>Number</th>
										<th>Issue Date</th>
										<th>Exp Date</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->legal_document_church as $key => $ldc)
										<tr>
											<td>{{$ldc->legal_document_id}}</td>
											<td>{{$ldc->number_document}}</td>
											<td>{{$ldc->issue_date}}</td>
											<td>{{$ldc->exp_date}}</td>
											<td>{{$ldc->status_document}}</td>
											<td>
											<a href="{{url('admin/legaldocumentchurch/'.$ldc->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/legaldocumentchurch/'.$ldc->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
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
			  		Service Time
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						<a href ="{{url('admin/servicetimechurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Service Time</a>
							<table id ="tableServiceTime" class = "table table-striped">
								<thead>
									<tr >
										<th>Service</th>
										<th>Time</th>
										<th>Room</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->service_type_church as $key => $stc)
										<tr>
											<td>{{$stc->service_type_id}}</td>
											<td>{{$stc->service_time}}</td>
											<td>{{$stc->service_room}}</td>
											<td>
											<a href="{{url('admin/servicetimechurch/'.$stc->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/servicetimechurch/'.$stc->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
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
						<a href ="{{url('admin/statushistorychurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Status</a>
							<table id ="tableStatusHistory" class = "table table-striped">
								<thead>
									<tr>
										<th>Status</th>
										<th>Reason</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->status_history_church as $key => $shc)
										<tr>
											<td>{{$shc->status}}</td>
											<td>{{$shc->reason}}</td>
											<td>{{$shc->date_status}}</td>
											<td>
											<a href="{{url('admin/statushistorychurch/'.$shc->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/statushistorychurch/'.$shc->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
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
			  		Related Entity
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						<a href ="{{url('admin/relatedentitychurch/create?churches_id='.$entry->id)}}"class = 'btn btn-primary btn-sm'>Add Related Entity</a>
							<table id ="tableRelatedEntity" class = "table table-striped">
								<thead>
									<tr >
										<th>No</th>
										<th>Entity</th>
										<th>Type</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->related_entity_church as $key => $rec)
										<tr>
											<td>{{$rec->id}}</td>
											<td>{{$rec->entity_church}}</td>
											<td>{{$rec->type_entity}}</td>
											<td>
											<a href="{{url('admin/relatedentitychurch/'.$rec->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/relatedentitychurch/'.$rec->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
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
			  		Structure
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						<a href ="{{url('admin/structurechurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Structure</a>
							<table id ="tableStructure" class = "table table-striped">
								<thead>
									<tr >
										<th>No</th>
										<th>Personal Name</th>
										<th>Title</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entry->ministry_role_church as $key => $mrc)
										<tr>
											<td>{{$mrc->id}}</td>
											<td>{{$mrc->personel_name}}</td>
											<td>{{$mrc->title_structure_id}}</td>
											<td>
											<a href="{{url('admin/structurechurch/'.$mrc->id.'/edit')}}"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this)" 
											data-route="{{ url('admin/structurechurch/'.$mrc->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
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
		$('#tableLegalDocument').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableServiceTime').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableStatusHistory').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableStructure').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		$('#tableRelatedEntity').DataTable();
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
