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
										<th>Type</th>
										<th>Contact Person</th>
										<th>Church Address</th>
										<th>Office Addresss</th>
										<th>Country</th>
										<th>Phone</th>
										<th>Fax</th>
										<th>E-mail</th>
										<th>Status</th>
										<th>Founded On</th>
										<th>Service Time</th>
									</tr>
								</thead>
								<tbody>
									@foreach($church_report_designs as $key => $church_report_design)
										<tr>
											<td>{{$church_report_design->rc_dpw_name}}</td>
											<td>{{$church_report_design->church_name}}</td>
											<td>{{$church_report_design->entities_type}}</td>
											<td>{{$church_report_design->contact_person}}</td>
											<td>{{$church_report_design->church_address}}</td>
											<td>{{$church_report_design->office_address}}</td>
											<td>{{$church_report_design->country_name}}</td>
											<td>{{$church_report_design->phone}}</td>
											<td>{{$church_report_design->fax}}</td>
											<td>{{$church_report_design->first_email}}</td>
											<td>{{$church_report_design->church_status}}</td>
											<td>{{$church_report_design->founded_on}}</td>
											<td>{{$church_report_design->service_time}}</td>
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
  	</style>
@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

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
			dom: 'Bfrtip',
			language: {
				searchPanes: {
					clearMessage: 'Clear All',
					collapse: {0: 'Filter By', _: 'Filter By (%d)'}
				}
			},
			buttons: [
				{ extend: 'excel',
				text: 'Export to Excel',
				title: 'Church Report',
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
			]
		} );
    $( "<hr>" ).insertAfter( ".buttons-excel" );
    $(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  </script>

@endsection