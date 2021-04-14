@extends(backpack_view('blank'))
@section('content')

  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5 col-md-4">
              <div class="icon-big text-center icon-warning">
                <i class="la la-church text-primary"></i>
              </div>
            </div>
            <div class="col-7 col-md-8">
              <div class="numbers">
                <h4 class="card-category">Churches</h4>
                <h2 class="card-title">{{$church_count}}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5 col-md-4">
              <div class="icon-big text-center icon-warning">
                <i class="la la-globe text-primary"></i>
              </div>
            </div>
            <div class="col-7 col-md-8">
              <div class="numbers">
                <h4 class="card-category">Countries</h4>
                <h2 class="card-title">{{$country_count}}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5 col-md-4">
              <div class="icon-big text-center icon-warning">
                <i class="las la-users text-primary"></i>
              </div>
            </div>
            <div class="col-7 col-md-8">
              <div class="numbers">
                <h4 class="card-category">Personnels</h4>
                <h2 class="card-title">{{$personel_count}}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5 col-md-4">
              <div class="icon-big text-center icon-warning">
                <i class="la la-birthday-cake text-primary"></i>
              </div>
            </div>
            <div class="col-7 col-md-8">
              <div class="numbers">
                <h5 class="card-category">Today's Birthday</h5>
                <h3 class="card-title"></h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	By Type
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableType" class = "table table-striped">
								<thead>
									<tr>
										<th>Church Type</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($type_tables as $key => $type_table)
										<tr>
											<td>{{$type_table->entities_type}}</td>
											<td>{{$type_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	By Country
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableCountry" class = "table table-striped">
								<thead>
									<tr>
										<th>Countries</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($country_tables as $key => $country_table)
										<tr>
											<td>{{$country_table->country_name}}</td>
											<td>{{$country_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Personnel
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePersonel" class = "table table-striped">
								<thead>
									<tr>
										<th>Personnel</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($personel_tables as $key => $personel_table)
										<tr>
											<td>{{$personel_table->long_desc}}</td>
											<td>{{$personel_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	By Regional Council
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableRCDPW" class = "table table-striped">
								<thead>
									<tr>
										<th>RC / DWP</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($rcdpw_tables as $key => $rcdpw_table)
										<tr>
											<td>{{$rcdpw_table->rc_dpw_name}}</td>
											<td>{{$rcdpw_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Personel VIP
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePersonelVip" class = "table table-striped">
								<thead>
									<tr>
										<th>Special Role</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($personel_vip_tables as $key => $personel_vip_table)
										<tr>
											<td>{{$personel_vip_table->special_role}}</td>
											<td>{{$personel_vip_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Ministry Role
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableMinistryRole" class = "table table-striped">
								<thead>
									<tr>
										<th>Ministry Role</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($ministry_role_tables as $key => $ministry_role_table)
										<tr>
											<td>{{$ministry_role_table->ministry_role}}</td>
											<td>{{$ministry_role_table->total}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Pastor's Birthday
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorBirthday" class = "table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastors_birthday_tables as $key => $pastors_birthday_table)
										<tr>
											<td>{{$pastors_birthday_table->first_name}}</td>
											<td>{{$pastors_birthday_table->date_of_birth}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Pastor's Anniversary
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorAnniversary" class = "table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastors_anniversary_tables as $key => $pastors_anniversary_table)
										<tr>
											<td>{{$pastors_anniversary_table->first_name}}</td>
											<td>{{$pastors_anniversary_table->anniversary}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Quicks Stats
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableQuickStats" class = "table table-striped">
								<thead>
									<tr>
										<th>New Pastor This Month</th>
										<th>New Church This Month</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	ID Card Expiration
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableIdCardExpired" class = "table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	License Expiration
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableLicenseExpired" class = "table table-striped">
								<thead>
									<tr>
										<th>Document</th>
										<th>Date</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Recently Inactive Church
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableInactiveChurch" class = "table table-striped">
								<thead>
									<tr>
										<th>Church's Name</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0">
			  	Recently Inactive Pastor
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableInactivePastor" class = "table table-striped">
								<thead>
									<tr>
										<th>Pastor's Name</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
@endsection

@section('after_styles')
	<style>
        .icon-big{font-size:4em;min-height:69px}
    </style>

@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	
	<script>
		$(document).ready(function() {
		$('#tableType').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableCountry').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tablePersonel').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableRCDPW').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tablePersonelVip').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableMinistryRole').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tablePastorBirthday').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tablePastorAnniversary').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableQuickStats').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableIdCardExpired').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableLicenseExpired').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableInactiveChurch').DataTable();
		} );
	</script>
  <script>
		$(document).ready(function() {
		$('#tableInactivePastor').DataTable();
		} );
	</script>
@endsection