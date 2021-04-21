@extends(backpack_view('blank'))
@section('content')

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Church List
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableAllChurch" class = "table table-striped">
								<thead>
									<tr>
										<th>Church Name</th>
										<th>Type</th>
										<th>RC / DPW</th>
										<th>Church Address</th>
										<th>Office Address</th>
										<th>City</th>
										<th>Province</th>
										<th>Postal Code</th>
										<th>Country</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Founded On</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach($all_church_tables as $key => $all_church_table)
										<tr>
											<td>{{$all_church_table->church_name}}</td>
											<td>{{$all_church_table->entities_type}}</td>
											<td>{{$all_church_table->rc_dpw_name}}</td>
											<td>{{$all_church_table->church_address}}</td>
											<td>{{$all_church_table->office_address}}</td>
											<td>{{$all_church_table->city}}</td>
											<td>{{$all_church_table->province}}</td>
											<td>{{$all_church_table->postal_code}}</td>
											<td>{{$all_church_table->country_name}}</td>
											<td>{{$all_church_table->first_email}}</td>
											<td>{{$all_church_table->phone}}</td>
											<td>{{$all_church_table->founded_on}}</td>
											<td>{{$all_church_table->church_status}}</td>
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

    <style>
    .active{
      background:aliceblue;
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
		$('#tableAllChurch').DataTable( {
			dom: 'Bfrtip',
			buttons: [
				{extend: 'excel', 
				text: 'Export to Excel', 
				title: 'New Church This Year',
				},
			]
		} );
		$( "<hr>" ).insertAfter( ".buttons-excel" );
    	$(".dt-button").addClass("btn btn-sm btn btn-outline-primary");
		} );
  	</script>

@endsection