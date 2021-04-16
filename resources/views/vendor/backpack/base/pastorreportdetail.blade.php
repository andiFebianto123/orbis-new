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
							<table id ="tablePastorAnnual" class = "table table-striped">
								<thead>
									<tr>
										<th>Pastor</th>
										<th>RC / DPW</th>
										<th>Country</th>
										<th>Status</th>
										<th>First Licensed On</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastor_report_detail_tables as $key => $pastor_report_detail_table)
										<tr>
											<td>{{$pastor_report_detail_table->first_name}}</td>
											<td>{{$pastor_report_detail_table->rc_dpw_name}}</td>
											<td>{{$pastor_report_detail_table->country_name}}</td>
											<td>{{$pastor_report_detail_table->acc_status}}</td>
											<td>{{$pastor_report_detail_table->first_lisenced_on}}</td>
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
		$('#tablePastorAnnual').DataTable();
		} );
	</script>

@endsection