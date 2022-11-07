@extends(backpack_view('blank'))
@section('content')

<div class="row">
    <div class="col-md-12">
  		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Pastor List
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableAllPastor" class = "table table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>RC / DPW</th>
										<th>Title</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Gender</th>
										<th>Church Name</th>
										<th>Address</th>
										<th>City</th>
										<th>Province / State</th>
										<th>Postal Code</th>
										<th>Country</th>
										<th>Phone</th>
										<th>Fax</th>
										<th>Email</th>
										<th>Marital Status</th>
										<th>Date of Birth</th>
										<th>Spouse Name</th>
										<th>Spouse Date of Birth</th>
										<th>Anniversary</th>
										<th>Status</th>
										<th>First Licensed On</th>
										<th>Card</th>
										<th>Valid Card Start</th>
										<th>Valid Card End</th>
										<th>Current Certificate Number</th>
										<th>Notes</th>
									</tr>
								</thead>
								<tbody>
									@foreach($all_pastor_tables as $key => $all_pastor_table)
										<tr>
											<td></td>
											<td>{!! $all_pastor_table->rc_dpw_name !!}</td>
											<td>{{$all_pastor_table->short_desc}}</td>
											<td>{{$all_pastor_table->first_name}}</td>
											<td>{{$all_pastor_table->last_name}}</td>
											<td>{{$all_pastor_table->gender}}</td>
											<td>{{$all_pastor_table->church_name}}</td>
											<td>{{$all_pastor_table->street_address}}</td>
											<td>{{$all_pastor_table->city}}</td>
											<td>{{$all_pastor_table->province}}</td>
											<td>{{$all_pastor_table->postal_code}}</td>
											<td>{{$all_pastor_table->country_name}}</td>
											<td>{{$all_pastor_table->phone}}</td>
											<td>{{$all_pastor_table->fax}}</td>
											<td>{{$all_pastor_table->email}}</td>
											<td>{{$all_pastor_table->marital_status}}</td>
											<td>{{$all_pastor_table->date_of_birth}}</td>
											<td>{{$all_pastor_table->spouse_name}}</td>
											<td>{{$all_pastor_table->spouse_date_of_birth}}</td>
											<td>{{$all_pastor_table->anniversary}}</td>
											<td>{{ App\Models\StatusHistory::leftJoin('status_histories as temps', function($leftJoin){
													$leftJoin->on('temps.personel_id', 'status_histories.personel_id')
													->where(function($innerQuery){
														$innerQuery->whereRaw('status_histories.date_status < temps.date_status')
														->orWhere(function($deepestQuery){
															$deepestQuery->whereRaw('status_histories.date_status = temps.date_status')
															->whereRaw('status_histories.id < temps.id');
														});
													});
												})->whereNull('temps.id')
												->join('account_status', 'account_status.id', 'status_histories.status_histories_id')
												->where('status_histories.personel_id', $all_pastor_table->id)
												->select('account_status.acc_status')->first()->acc_status ?? '-'
												}}
											</td>
											<td>{{$all_pastor_table->first_licensed_on}}</td>
											<td>{{$all_pastor_table->card}}</td>
											<td>{{$all_pastor_table->valid_card_start}}</td>
											<td>{{$all_pastor_table->valid_card_end}}</td>
											<td>{{$all_pastor_table->current_certificate_number}}</td>
											<td>{{$all_pastor_table->notes}}</td>
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
</div>
@endsection

@section('after_styles')
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <style>
		.active{
		background:aliceblue;
		}

		.bg-light {
			background-color: #f9fbfd !important;
		}

		body{
			background: #f9fbfd;
		}
	</style>
@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

	<script src ="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src ="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>

	<script>
		$(document).ready(function() {
		var t = $('#tableAllPastor').DataTable( {
        	"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
        	} ],
        	"order": [[ 1, 'asc' ]],
			"scrollY": 400,
        	"scrollX": true,
			"pagingType": "simple_numbers",
			dom: 'Bfrtip',
			buttons: [
				{extend: 'excel', 
				text: 'Export to Excel', 
				title: 'All Pastor',
				},
			]
		} );
		
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
    	} ).draw();
		$( "<hr>" ).insertAfter( ".buttons-excel" );
    	$(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  	</script>

@endsection