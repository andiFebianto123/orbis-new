@extends(backpack_view('blank'))
@section('content')

<nav class="navbar navbar-expand-sm bg-light">
	<ul class="navbar-nav">	
    	<button class="btn btn-outline-primary" type="button"><a href="{{url('admin/pastorreport/')}}">Pastor Annual Report</a></button>
    	<button class="btn btn-outline-primary" type="button"><a href="{{url('admin/pastorreportdesigner/')}}">Report Designer</a></button>
  	</ul>
</nav>

<div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Pastor List
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorDetail" class = "table table-striped">
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
		$('#tablePastorDetail').DataTable( {
			dom: 'Bfrtip',
			buttons: [
				{extend: 'excel', 
				text: 'Export to Excel', 
				title: 'Pastor List'},
			]
		} );
		} );
  	</script>

@endsection