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
                <h4 class="card-category"> Active Churches</h4>
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
                <h4 class="card-category">Active Personnels</h4>
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
		  <div id="birthdayButton" class="birthday">
          <div class="row">
				<div class="col-5 col-md-4">
				<div class="icon-big text-center icon-warning">
					<i class="la la-birthday-cake text-primary"></i>
				</div>
				</div>
				<div class="col-7 col-md-8">
				<div class="numbers">
						<h5 class="card-category">Today's Birthday</h5>
						<h3 class="card-title">{{$today_birthday}}</h3>
				</div>
			</div>
          </div>
		</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
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
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;" id="headerPastorBirthday">
			  	Pastor's Birthday
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorBirthday" class = "table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastors_birthday_tables as $key => $pastors_birthday_table)
										<tr>
											<td>{{$pastors_birthday_table->short_desc}}</td>
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
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Pastor's Anniversary
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tablePastorAnniversary" class = "table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pastors_anniversary_tables as $key => $pastors_anniversary_table)
										<tr>
											<td>{{$pastors_anniversary_table->short_desc}}</td>
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
	<div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				New Pastor This Month
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableQuickStatsNewPastor" class = "table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($new_pastor_tables as $key => $new_pastor_table)
										<tr>
											<td>{{$new_pastor_table->short_desc}}</td>
											<td>{{$new_pastor_table->first_name}}</td>
											<td>{{$new_pastor_table->valid_card_start}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				New Church This Month
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableQuickStatsNewChurch" class = "table table-striped">
								<thead>
									<tr>
										<th>Church Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($new_church_tables as $key => $new_church_table)
										<tr>
											<td>{{$new_church_table->church_name}}</td>
											<td>{{$new_church_table->founded_on}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	ID Card Expiration
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableIdCardExpired" class = "table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Name</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($id_card_expiration_tables as $key => $id_card_expiration_tables)
										<tr>
											<td>{{$id_card_expiration_tables->short_desc}}</td>
											<td>{{$id_card_expiration_tables->first_name}}</td>
											<td>{{$id_card_expiration_tables->valid_card_end}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	License Expiration
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableLicenseExpired" class = "table table-striped">
								<thead>
									<tr>
										<th>Church Name</th>
										<th>Document</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($license_expiration_tables as $key => $license_expiration_table)
										<tr>
											<td>{{$license_expiration_table->church_name}}</td>
											<td>{{$license_expiration_table->documents}}</td>
											<td>{{$license_expiration_table->exp_date}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				Recently Inactive Church (Last 30 days)
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableInactiveChurch" class = "table table-striped">
								<thead>
									<tr>
										<th>Church's Name</th>
										<th>Inactive Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($inactive_church_tables as $key => $inactive_church_table)
										<tr>
											<td>{{$inactive_church_table->church_name}}</td>
											<td>{{$inactive_church_table->date_status}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    <div class="col-md-6">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				Recently Inactive Pastor (Last 30 days)
  			</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableInactivePastor" class = "table table-striped">
								<thead>
									<tr>
										<th>Pastor's First Name</th>
										<th>Last Name</th>
										<th>Inactive Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach($inactive_pastor_tables as $key => $inactive_pastor_table)
										<tr>
											<td>{{$inactive_pastor_table->first_name}}</td>
											<td>{{$inactive_pastor_table->last_name}}</td>
											<td>{{$inactive_pastor_table->date_status}}</td>
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
@endsection

@section('after_styles')
	<style>
        .icon-big{font-size:4em;min-height:69px}
		.dataTables_paginate .next {
    		display: none;
		}
		.dataTables_paginate .previous {
    		display: none;
		}
		.birthday{
			cursor: pointer;
		}
    </style>
@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	
	<script>
		$(document).ready(function() {
		$('#tableType').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>

  	<script>
		$(document).ready(function() {
		$('#tableCountry').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>

  	<script>
		$(document).ready(function() {
		$('#tablePersonel').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableRCDPW').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tablePersonelVip').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableMinistryRole').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$("#birthdayButton").click((e)=>{
			$('html,body').animate({scrollTop: $('#headerPastorBirthday').offset().top}, 2000, function() {
				$('#headerPastorBirthday').focus();
			});
		})
		$('#tablePastorBirthday').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tablePastorAnniversary').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableQuickStatsNewPastor').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
	<script>
		$(document).ready(function() {
		$('#tableQuickStatsNewChurch').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableIdCardExpired').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableLicenseExpired').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableInactiveChurch').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
  	<script>
		$(document).ready(function() {
		$('#tableInactivePastor').DataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false});
		} );
	</script>
@endsection