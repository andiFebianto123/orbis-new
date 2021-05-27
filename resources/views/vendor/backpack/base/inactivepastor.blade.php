@extends(backpack_view('blank'))
@section('content')

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Inactive Pastor This Year
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableInactivePastor" class = "table table-striped">
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
										<th>First Licensed On</th>
										<th>Card</th>
										<th>Valid Card Start</th>
										<th>Valid Card End</th>
										<th>Current Certificate Number</th>
										<th>Notes</th>
										<th>Status</th>
										<th>Inactive Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($inactive_pastor_reports as $key => $inactive_pastor_report)
										<tr>
											<td>{{$inactive_pastor_report->rc_dpw_name}}</td>
											<td>{{$inactive_pastor_report->short_desc}}</td>
											<td>{{$inactive_pastor_report->first_name}}</td>
											<td>{{$inactive_pastor_report->last_name}}</td>
											<td>{{$inactive_pastor_report->gender}}</td>
											<td>{{$inactive_pastor_report->church_name}}</td>
											<td>{{$inactive_pastor_report->street_address}}</td>
                      						<td>{{$inactive_pastor_report->city}}</td>
											<td>{{$inactive_pastor_report->province}}</td>
											<td>{{$inactive_pastor_report->postal_code}}</td>
                      						<td>{{$inactive_pastor_report->country_name}}</td>
											<td>{{$inactive_pastor_report->phone}}</td>
											<td>{{$inactive_pastor_report->fax}}</td>
                      						<td>{{$inactive_pastor_report->email}}</td>
											<td>{{$inactive_pastor_report->marital_status}}</td>
											<td>{{$inactive_pastor_report->date_of_birth}}</td>
											<td>{{$inactive_pastor_report->spouse_name}}</td>
                      						<td>{{$inactive_pastor_report->spouse_date_of_birth}}</td>
											<td>{{$inactive_pastor_report->anniversary}}</td>
											<td>{{$inactive_pastor_report->first_licensed_on}}</td>
											<td>{{$inactive_pastor_report->card}}</td>
                      						<td>{{$inactive_pastor_report->valid_card_start}}</td>
											<td>{{$inactive_pastor_report->valid_card_end}}</td>
											<td>{{$inactive_pastor_report->current_certificate_number}}</td>
											<td>{{$inactive_pastor_report->notes}}</td>
											<td>{{$inactive_pastor_report->acc_status}}</td>
											<td>{{$inactive_pastor_report->date_status}}</td>
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
		$('#tableInactivePastor').DataTable( {
			"scrollY": true,
        	"scrollX": true,
			"pagingType": "simple_numbers",
			dom: 'Bfrtip',
			buttons: [
				{extend: 'excel', 
				text: 'Export to Excel', 
				title: 'Inactive Church This Year',
				},
			]
		} );
		$( "<hr>" ).insertAfter( ".buttons-excel" );
    	$(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  	</script>

@endsection