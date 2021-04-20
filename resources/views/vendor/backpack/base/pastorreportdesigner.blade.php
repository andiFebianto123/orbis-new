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
										<th>Regional Council</th>
										<th>Name</th>
										<th>Address</th>
										<th>Phone</th>
										<th>Fax</th>
                    <th>Email</th>
                    <th>Card</th>
                    <th>Date of Birth</th>
                    <th>Spouse Name</th>
                    <th>Spouse Date of Birth</th>
                    <th>Anniversary</th>
                    <th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastor_report_designs as $key => $pastor_report_design)
										<tr>
											<td>{{$pastor_report_design->rc_dpw_name}}</td>
											<td>{{$pastor_report_design->first_name}}</td>
											<td>{{$pastor_report_design->street_address}}</td>
											<td>{{$pastor_report_design->phone}}</td>
											<td>{{$pastor_report_design->fax}}</td>
                      <td>{{$pastor_report_design->email}}</td>
											<td>{{$pastor_report_design->card}}</td>
											<td>{{$pastor_report_design->date_of_birth}}</td>
                      <td>{{$pastor_report_design->spouse_name}}</td>
											<td>{{$pastor_report_design->spouse_date_of_birth}}</td>
											<td>{{$pastor_report_design->anniversary}}</td>
                      <td>{{$pastor_report_design->acc_status}}</td>
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

  <script src ="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src ="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
	<script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
  <script src ="https://cdn.datatables.net/buttons/1.7.0/js/buttons.colVis.min.js"></script>
	
  <script>
		$(document).ready(function() {
		$('#tablePastorReportDesigner').DataTable( {
			dom: 'Bfrtip',
			buttons: [
				{ extend: 'excel',
          text: 'Export to Excel',
          title: 'Pastor Report',
          exportOptions: {
            columns: ':visible'
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