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
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
									<td> : {{
											App\Models\StatusHistoryChurch::leftJoin('status_history_churches as temps', function($leftJoin){
													$leftJoin->on('temps.churches_id', 'status_history_churches.churches_id')
													->where(function($innerQuery){
														$innerQuery->whereRaw('status_history_churches.date_status < temps.date_status')
														->orWhere(function($deepestQuery){
															$deepestQuery->whereRaw('status_history_churches.date_status = temps.date_status')
															->where('status_history_churches.id', '<', 'temps.id');
														});
													});
												})->whereNull('temps.id')
												->where('status_history_churches.churches_id', $entry->id)
												->select('status_history_churches.churches_id', 'status_history_churches.status')->first()->status ?? '-'
										}} </td>
								</tr>
								<tr>
									<td>Founded On</td>
									<td> : {{ $entry->founded_on }}</td>
								</tr>
								<tr>
									<td> Church Type</td>
									<td> :  
									@if(isset($entry->church_type->entities_type))
									{{ $entry->church_type->entities_type }}
									@else
									-
									@endif
									</td>
								</tr>
								<tr>
									<td>Local Church</td>
									<td> : {{
										App\Models\Church::where('id', $entry->church_local_id)->select('church_name')->first()->church_name ?? '-'
											}}
									</td>
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
									<td style="white-space: pre-line;" > : {{ $entry->church_address }}</td>
								</tr>
								<tr>
									<td>Office Address</td>
									<td style="white-space: pre-line;" > :  {{ $entry->office_address }}</td>
								</tr>
								<tr>
									<td>City</td>
									<td> :  {{ $entry->city }}</td>
								</tr>
								<tr>
									<td>State</td>
									<td> :  {{ $entry->province }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class = "table table-striped">
								<tr>
									<td>Postcode</td>
									<td> :  {{ $entry->postal_code }}</td>
								</tr>
								<tr>
									<td>Country</td>
									<td> :  {{ $entry->country->country_name ?? '-'}}</td>
								</tr>
								<tr>
									<td>Email</td>
									<td> :  {{ $entry->first_email }}</td>
								</tr>
								<tr>
									<td>Email (Secondary)</td>
									<td> :  {{ $entry->second_email }}</td>
								</tr>
								<tr>
									<td>Phone</td>
									<td style="white-space: pre-line;" > :  {{ $entry->phone }}</td>
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
								<tr>
									<td>Service Time</td>
									<td> :  {{ $entry->service_time_church }}</td>
								</tr>
								<tr>
									<td>Certificate / SK</td>
									<td> : <img width="150px" style="margin:15px" src="{{str_replace('public', '', URL::to('/'))}}{{ str_replace('storage', '/storage', $entry->certificate) }}" alt=""></td>
								</tr>
								<tr>
									<td>Date of Certificate</td>
									<td> :  {{ $entry->date_of_certificate }}</td>
								</tr>
								<tr>
									<td>Notes</td>
									<td> :  {{ $entry->notes }}</td>
								</tr>
							</table>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-md-12">
  			<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
					Church Status Histories
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						@if(backpack_user()->hasRole(['Super Admin','Editor']))
							<a href ="{{url('admin/statushistorychurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Status</a>
						@endif
							<table id ="tableStatusHistory" class = "table table-striped">
								<thead>
									<tr>
										<th>Status</th>
										<th>Reason</th>
										<th>Date</th>
										@if(backpack_user()->hasRole(['Super Admin','Editor']))
										<th class="hidden-print">Action</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($entry->status_history_church as $key => $shc)
										<tr>
											<td>{{$shc->status ?? '-'}}</td>
											<td>{{$shc->reason}}</td>
											<td>{{$shc->date_status}}</td>
											@if(backpack_user()->hasRole(['Super Admin','Editor']))
											<td>
											<a href="{{url('admin/statushistorychurch/'.$shc->id.'/edit?churches_id='.$entry->id)}}" class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this, 'table')" 
											data-route="{{ url('admin/statushistorychurch/'.$shc->id.'?churches_id='.$entry->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
											@endif
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
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  		Related Entity Church (Foundation, Bussiness Unit, Non Profit Organization, etc)
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						@if(backpack_user()->hasRole(['Super Admin','Editor']))
							<a href ="{{url('admin/relatedentitychurch/create?churches_id='.$entry->id)}}"class = 'btn btn-primary btn-sm'>Add Related Entity</a>
						@endif
							<table id ="tableRelatedEntity" class = "table table-striped">
								<thead>
									<tr >
										<th>No.</th>
										<th>Entity Name</th>
										<th>Type</th>
										@if(backpack_user()->hasRole(['Super Admin','Editor']))
										<th class="hidden-print">Action</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($entry->related_entity_church as $key => $rec)
										<tr>
											<td></td>
											<td>{{$rec->entity_church}}</td>
											<td>{{$rec->type_entity}}</td>
											@if(backpack_user()->hasRole(['Super Admin','Editor']))
											<td>
											<a href="{{url('admin/relatedentitychurch/'.$rec->id.'/edit?churches_id='.$entry->id)}}" class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this, 'table')" 
											data-route="{{ url('admin/relatedentitychurch/'.$rec->id.'?churches_id='.$entry->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
											@endif
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
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
					Leadership Structure
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						@if(backpack_user()->hasRole(['Super Admin','Editor']))
							<a href ="{{url('admin/structurechurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Structure</a>
						@endif
							<table id ="tableLeadershipStructure" class = "table table-striped">
								<thead>
									<tr >
										<th>No.</th>
										<th>Title</th>
										<th>Name</th>
										<th>Role</th>
										@if(backpack_user()->hasRole(['Super Admin','Editor']))
										<th class="hidden-print">Action</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($leaderships as $key => $mrc)
										<tr>
											<td></td>
											<td>{{$mrc->short_desc}}</td>
											<td>{{$mrc->first_name." ".$mrc->last_name}}</td>
											<td>{{$mrc->ministry_role}}</td>
											@if(backpack_user()->hasRole(['Super Admin','Editor']))
											<td>
											<a href="{{url('admin/structurechurch/'.$mrc->id.'/edit?churches_id='.$entry->id)}}" class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this, 'table')" 
											data-route="{{ url('admin/structurechurch/'.$mrc->id.'?churches_id='.$entry->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
											@endif
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
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
					Coordinator Church
  				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
						@if(backpack_user()->hasRole(['Super Admin','Editor']))
							<a href ="{{url('admin/coordinatorchurch/create?churches_id='.$entry->id)}}" class = 'btn btn-primary btn-sm'>Add Structure</a>
						@endif
							<table id ="tableCoordinatorChurch" class = "table table-striped">
								<thead>
									<tr >
										<th>No.</th>
										<th>Title</th>
										<th>Coordinator Name</th>
										@if(backpack_user()->hasRole(['Super Admin','Editor']))
										<th class="hidden-print">Action</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($entry->coordinator_church as $key => $cc)
										<tr>
											<td></td>
											<td>{{$cc->coordinator_title}}</td>
											<td>{{$cc->coordinator_name}}</td>
											@if(backpack_user()->hasRole(['Super Admin','Editor']))
											<td>
											<a href="{{url('admin/coordinatorchurch/'.$cc->id.'/edit?churches_id='.$entry->id)}}" class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
											<a href="javascript:void(0)" onclick="deleteEntry(this, 'table')" 
											data-route="{{ url('admin/coordinatorchurch/'.$cc->id.'?churches_id='.$entry->id ) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i></a>
											</td>
											@endif
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
				@if(backpack_user()->hasRole(['Super Admin','Editor']))
					@if ($crud->buttons()->where('stack', 'line')->count())
						<tr>
							<td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
							<td>
								<a href="{{url('admin/church/'.$entry->id.'/edit')}}" class="btn btn-sm btn-link"><i class="la la-edit"></i> Edit</a>
								<a href="javascript:void(0)" onclick="deleteEntry(this, 'parent')" data-route="{{url('admin/church/'.$entry->id)}}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> Delete</a>
								{{-- @include('crud::inc.button_stack', ['stack' => 'line']) --}}
							</td>
						</tr>
					@endif
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

	<style>

		@media print {
			.btn-primary{
				display:none;
			}
			.dataTables_info{
				display:none;
			}
			.dataTables_paginate{
				display:none;
			}
			.dataTables_filter{
				display:none;
			}
			.dataTables_length{
				display:none;
			}
			.btn-link{
				display:none;
			}
			.la-edit{
				display:none;
			}
			.no-padding{
				display:none;
			}
			.hidden-print{
				display:none;
			}
			.sorting:before{
				visibility:hidden;
			}
			.sorting:after{
				visibility:hidden;
			}
		}

	</style>

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
		$('#tableStatusHistory').DataTable();
		} );
	</script>

	<script>
		$(document).ready(function() {
		var t = $('#tableLeadershipStructure').DataTable( {
        	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        	} ],
        	"order": [[ 1, 'asc' ]]
    	} );
 
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		} );


		$(document).ready(function() {
		var t = $('#tableCoordinatorChurch').DataTable( {
        	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        	} ],
        	"order": [[ 1, 'asc' ]]
    	} );
 
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		} );
	</script>

	<script>
		$(document).ready(function() {
		var t = $('#tableRelatedEntity').DataTable( {
        	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        	} ],
        	"order": [[ 1, 'asc' ]]
    	} );
 
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		} );
	</script>

	<script>
		if (typeof deleteEntry != 'function') {
		$("[data-button-type=delete]").unbind('click');

		function deleteEntry(button, typeRedirect) {
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
							$('table').css("opacity", "0.4")
							$('a').removeAttr("href")
							$('button').attr("disabled", "disabled")
							// $('.modal').modal('hide');
							var redirectTo = "{{url('admin/church')}}/{{$entry->id}}/show"
							switch (typeRedirect) {
								case 'parent':
									redirectTo = "{{url('admin/church')}}"
									break;
								case 'table':
									redirectTo = "{{url('admin/church')}}/{{$entry->id}}/show"
									break;
								default:
									break;
							}
							setTimeout(() => { 
								window.location = redirectTo
							}, 2000);
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
