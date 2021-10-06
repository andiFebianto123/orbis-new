@extends(backpack_view('blank'))
@section('content')

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="{{url('admin/pastorreport/')}}">Pastor Annual Report</a>
  </li>
  <li class="nav-item">
  <a class="nav-link active" aria-current="page" href="{{url('admin/pastorreportdesigner/')}}">Report Designer</a>
  </li>
</ul>

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Report Designer
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorReportDesigner" class = "table table-striped">
								<thead>
									<tr>
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
									@foreach($pastor_report_designs as $key => $pastor_report_design)
										<tr>
											<td>{{$pastor_report_design->rc_dpw_name}}</td>
											<td>{{$pastor_report_design->short_desc}}</td>
											<td>{{$pastor_report_design->first_name}}</td>
											<td>{{$pastor_report_design->last_name}}</td>
											<td>{{$pastor_report_design->gender}}</td>
											<td>{{$pastor_report_design->church_name}}</td>
											<td>{{$pastor_report_design->street_address}}</td>
                      						<td>{{$pastor_report_design->city}}</td>
											<td>{{$pastor_report_design->province}}</td>
											<td>{{$pastor_report_design->postal_code}}</td>
                      						<td>{{$pastor_report_design->country_name}}</td>
											<td>{{$pastor_report_design->phone}}</td>
											<td>{{$pastor_report_design->fax}}</td>
                      						<td>{{$pastor_report_design->email}}</td>
											<td>{{$pastor_report_design->marital_status}}</td>
											<td>{{$pastor_report_design->date_of_birth}}</td>
											<td>{{$pastor_report_design->spouse_name}}</td>
                      						<td>{{$pastor_report_design->spouse_date_of_birth}}</td>
											<td>{{$pastor_report_design->anniversary}}</td>
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
												->where('status_histories.personel_id', $pastor_report_design->id)
												->select('account_status.acc_status')->first()->acc_status ?? '-'
												}}
											</td>
											<td>{{$pastor_report_design->first_licensed_on}}</td>
											<td>{{$pastor_report_design->card}}</td>
                      						<td>{{$pastor_report_design->valid_card_start}}</td>
											<td>{{$pastor_report_design->valid_card_end}}</td>
											<td>{{$pastor_report_design->current_certificate_number}}</td>
											<td>{{$pastor_report_design->notes}}</td>
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

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/1.2.1/css/searchPanes.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
	
	<style>
		.active{
			background:darkblue;
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
  	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.colVis.min.js"></script>

	<script src ="https://cdn.datatables.net/searchpanes/1.2.1/js/dataTables.searchPanes.min.js"></script>
	<script src ="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	
  	<script>
		$(document).ready(function() {
		$('#tablePastorReportDesigner').DataTable( {
			"scrollY": 400,
        	"scrollX": true,
			dom: 'Bfrtip',
			language: {
				searchPanes: {
					clearMessage: 'Clear All',
					collapse: {0: 'Filter By', _: 'Filter By (%d)'},
					count: '{total} found',
                	countFiltered: '{shown} / {total}'
				}
			},
			buttons: [
				{ extend: 'excel',
					text: 'Export to Excel',
					title: 'Pastor Report',
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'searchPanes',
					config: {
						cascadePanes: true
					}
				},
				'columnsToggle'
			],

			columnDefs: [
				{
					searchPanes: {
						show: true
					},
					targets: [0 , 1, 10, 19, 21, 23]
				},

				{
					searchPanes: {
						show: false
					},
					targets: [2 , 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, 22, 24, 25]
				}
			]

		} );
    $( "<hr>" ).insertAfter( ".buttons-excel" );
    $(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  </script>
	
@endsection

