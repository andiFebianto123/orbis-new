@extends(backpack_view('blank'))
@section('content')

<div class="row">
	<div class="col-md-12">
  		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Import Church
  			</div>
			<div class="card-body">
				<div class = "row">
					<div class="col-md-12">
						<form method= 'POST' action="{{url('admin/church-upload/')}}" class="btn btn-primary" enctype="multipart/form-data">
							@csrf
							<input type="file" name="fileToUpload" id="fileToUpload">
							<button Type='submit'>Upload</button>
						</form>
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
	
@endsection