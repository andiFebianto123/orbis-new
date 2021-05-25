@extends(backpack_view('blank'))
@section('content')

@if(session()->has('status'))
	<p class="alert alert-success">{{session('status')}}</p>
@endif

@if(session()->has('status_error'))
<p class="alert alert-danger">{{session('status_error')}}</p>
@endif

<div class="row">
	<div class="col-md-7">
  		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
			  	Import RC / DPW
  			</div>
		</div>
		<div class="center">
			<div class="col-md-7">
				<form action="{{url('admin/rcdpw-upload/')}}" method="POST" enctype="multipart/form-data">
					@csrf
					<input type="file" name="fileToUpload" id="fileToUpload">
					<p>Drag your files here or click in this area.</p>
					<button Type='submit'>Upload File</button>
				</form>
				@if ($errors->has('fileToUpload'))
					@foreach ($errors->get('fileToUpload') as $error)
						<p class= 'alert alert-danger text-small'>{{ $error }}</p>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

@section('after_styles')

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

	<style>
		.bg-light {
			background-color: #f9fbfd !important;
		}

		body{
			background: #f9fbfd;
		}

		.alert-danger {
			color: #721c24;
			background-color: #f8d7da;
			border-color: #f5c6cb;
			width: 300px;
		}

		.alert-success {
			color: #155724;
			background-color: #d4edda;
			border-color: #c3e6cb;
			width: 200px;
		}

		form{
			position: absolute;
			left: 40%;
			top: 150%;
			width: 400px;
			height: 200px;
			border: 4px dashed #b5c7e0;
			background: #fff;
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
			width: 400px;
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
		$('#tableLogError').DataTable();
		} );

		$(document).ready(function(){
		$('form input').change(function () {
			$('form p').text(this.files.length + " file(s) selected");
		});
		});
	</script>
	
@endsection