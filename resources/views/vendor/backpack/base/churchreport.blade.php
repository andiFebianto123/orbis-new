@extends(backpack_view('blank'))
@section('content')

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Church Annual Report
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableChurchAnnual" class = "table table-striped">
								<thead>
									<tr>
										<th>Year</th>
										<th>Churches</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($church_report_tables as $key => $church_report_table)
										<tr>
											<td>{{$church_report_table->year}}</td>
											<td>{{$church_report_table->total}}</td>
											<td><a href="{{url('admin/churchannualreportdetail/'.$church_report_table->year)}}"><i class="la la-eye"></i>Report Detail</a></td>
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
	
@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	
 	<script>
		$(document).ready(function() {
		$('#tableChurchAnnual').DataTable();
		} );
	</script>
@endsection