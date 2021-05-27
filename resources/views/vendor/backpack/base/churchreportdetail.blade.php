@extends(backpack_view('blank'))
@section('content')

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="{{url('admin/churchreport/')}}">Church Annual Report</a>
  </li>
  <li class="nav-item">
  	<a class="nav-link" href="{{url('admin/churchreportdesigner/')}}">Report Designer</a>
  </li>
</ul>

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Church List {{$year}}
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableChurchDetail" class = "table table-striped">
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
										<th>Province / State</th>
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
									@foreach($church_report_detail_tables as $key => $church_report_detail_table)
										<tr>
											<td>{{$church_report_detail_table->rc_dpw_name}}</td>
											<td>{{$church_report_detail_table->church_name}}</td>
											<td>{{$church_report_detail_table->entities_type}}</td>
											<td>{{$church_report_detail_table->lead_pastor_name}}</td>
											<td>{{$church_report_detail_table->contact_person}}</td>
											<td>{{$church_report_detail_table->church_address}}</td>
											<td>{{$church_report_detail_table->office_address}}</td>
											<td>{{$church_report_detail_table->city}}</td>
											<td>{{$church_report_detail_table->province}}</td>
											<td>{{$church_report_detail_table->postal_code}}</td>
											<td>{{$church_report_detail_table->country_name}}</td>
											<td>{{$church_report_detail_table->phone}}</td>
											<td>{{$church_report_detail_table->fax}}</td>
											<td>{{$church_report_detail_table->first_email}}</td>
											<td>{{$church_report_detail_table->church_status}}</td>
											<td>{{$church_report_detail_table->founded_on}}</td>
											<td>{{$church_report_detail_table->service_time_church}}</td>
											<td>{{$church_report_detail_table->notes}}</td>
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
		$('#tableChurchDetail').DataTable( {
			"scrollY": 300,
        	"scrollX": true,
			"pagingType": "simple_numbers",
			dom: 'Bfrtip',
			buttons: [
				{extend: 'excel', 
				text: 'Export to Excel', 
				title: 'Church List {{$year}}',
				},
			]
		} );
		$( "<hr>" ).insertAfter( ".buttons-excel" );
    	$(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  	</script>

@endsection