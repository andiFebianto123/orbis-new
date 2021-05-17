@extends(backpack_view('blank'))
@section('content')

@if(session()->has('status'))
	<p class="alert alert-success">{{session('status')}}</p>
@endif

@if (isset($failures))
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header" style="background: #f8d7da; font-weight:bold;">
					Log Errors
				</div>
				<div class="card-body">
					<div class = "row">
						<div class="col-md-12">
							<table id ="tableLogErrorChurch" class = "table table-striped">
								<thead>
									<tr>
										<th>Row</th>
										<th>Description</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($failures as $failure)
										@foreach ($failure['errors'] as $error)
											<tr>
												<td>{{$failure['row']}}</td>
												<td>{{$error}}</td>
											</tr>
										@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif

<div class="row">
	<div class="col-md-12">
  		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Import Church
  			</div>
		</div>
		<div class="center">
			<form action="{{url('admin/church-upload/')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="file" name="fileToUpload" id="fileToUpload">
				<p>Drag your files here or click in this area.</p>
				<button Type='submit'>Upload File</button>
			</form>
			@if ($errors->has('fileToUpload'))
				@foreach ($errors->get('fileToUpload') as $error)
					<p class= 'text-small text-danger'>{{ $error }}</p>
				@endforeach
			@endif
		</div>
	</div>
</div>

@endsection

@section('after_styles')

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

	<style>
		body{
			background: #fff;
		}

		form{
			position: absolute;
			left: 30%;
			top: 100%;
			width: 500px;
			height: 200px;
			border: 4px dashed #b5c7e0;
		}

		form p{
			width: 100%;
			height: 100%;
			text-align: center;
			line-height: 200px;
			color: #b5c7e0;
			font-family: Arial;
		}

		form input{
			position: absolute;
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
			outline: none;
			opacity: 0;
		}

		form button{
			margin: 0;
			color: #fff;
			background: #16a085;
			border: none;
			width: 500px;
			height: 35px;
			margin-top: -20px;
			margin-left: -4px;
			border-radius: 4px;
			border-bottom: 4px solid #117A60;
			transition: all .2s ease;
			outline: none;
		}

		form button:hover{
			background: #149174;
			color: #0C5645;
		}

		form button:active{
			border:0;
		}

	</style>
	
@endsection

@section('after_scripts')

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function() {
		$('#tableLogErrorChurch').DataTable();
		} );

		$(document).ready(function(){
		$('form input').change(function () {
			$('form p').text(this.files.length + " file(s) selected");
		});
		});
	</script>
	
@endsection