@extends(backpack_view('blank'))
@section('content')

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="{{url('admin/churchreport/')}}">Church Annual Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="{{url('admin/churchreportdesigner/')}}">Report Designer</a>
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
							<table id ="tableChurchReportDesigner" class = "table table-striped">
								<thead>
									<tr>
										<th>RC / DPW</th>
										<th>Church Name</th>
										<th>Church Type</th>
										<th>Lead Pastor Name</th>
										<th>Contact Person</th>
										<th>Church Address</th>
										<th>Office Address</th>
										<th>City</th>
										<th>Province</th>
										<th>Postal Code</th>
										<th>Country</th>
										<th>Phone</th>
										<th>Fax</th>
										<th>Email</th>
										<th>Church Status</th>
										<th>Founded On</th>
										<th>Service Time Church</th>
										<th>Notes</th>
									</tr>
								</thead>
								<tbody>
									@foreach($church_report_designs as $key => $church_report_design)
										<tr>
											<td>{{$church_report_design->rc_dpw_name}}</td>
											<td>{{$church_report_design->church_name}}</td>
											<td>{{$church_report_design->entities_type}}</td>
											<td style="white-space: pre-line;" > {{$church_report_design->lead_pastor_name}} </td>
											<td>{{$church_report_design->contact_person}}</td>
											<td>{{$church_report_design->church_address}}</td>
											<td>{{$church_report_design->office_address}}</td>
											<td>{{$church_report_design->city}}</td>
											<td>{{$church_report_design->province}}</td>
											<td>{{$church_report_design->postal_code}}</td>
											<td>{{$church_report_design->country_name}}</td>
											<td>{{$church_report_design->phone}}</td>
											<td>{{$church_report_design->fax}}</td>
											<td>{{$church_report_design->first_email}}</td>
											<td>{{$church_report_design->church_status}}</td>
											<td>{{$church_report_design->founded_on}}</td>
											<td>{{$church_report_design->service_time_church}}</td>
											<td>{{$church_report_design->notes}}</td>
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
	.dt-button : hover{
		color: black !important;
 		border: 1px solid black !important;
	}

	.bg-light {
		background-color: #f9fbfd !important;
	}

	body{
		background: #f9fbfd;
	}
	td {word-wrap: break-word}
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
		$('#tableChurchReportDesigner').DataTable( {
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
				{ 	extend: 'excel',
					text: 'Export to Excel',
					title: 'Church Report',
					exportOptions: {
						stripHtml: false,
						columns: ':visible',
						format: {
							body: function ( data, column, row ) {
								return column === 5 ?
									data.replace( /<br\s*\/?>/ig, "\n" ) :
									data;
							}
						}
					},
					customize: function( xlsx ) {
						var sheet = xlsx.xl.worksheets['sheet1.xml'];
						// $('row c', sheet).attr( 's', '55' );
						$('row c', sheet).each(function(index) {
							if (index > 0) {
								$(this).attr('ht', 60);
								$(this).attr('customHeight', 1);
								$(this).attr( 's', '55' );
							}
						});
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
					targets: [0, 2, 10]
				},

				{
					searchPanes: {
						show: false
					},
					targets: [1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17]
				}
			]

		} );
    $( "<hr>" ).insertAfter( ".buttons-excel" );
    $(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  </script>

@endsection