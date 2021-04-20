@extends(backpack_view('blank'))
@section('content')

  <div class="row">
    <div class="col-md-12">
  		<div class="card">
				<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Quick Report
  			</div>
				<div class="card-body">
          Select Option
					<div class = "row">
						<div class="col-md-12">
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Quick Report
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <li><a class="dropdown-item" href="#">New Church This Year</a></li>
                  <li><a class="dropdown-item" href="#">New Pastor This Year</a></li>
                  <li><a class="dropdown-item" href="#">Recently Inactive Church</a></li>
                  <li><a class="dropdown-item" href="#">Recently Inactive Pastor</a></li>
                  <li><a class="dropdown-item" href="#">All Church</a></li>
                  <li><a class="dropdown-item" href="#">All Personel</a></li>
                </ul>
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