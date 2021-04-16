@extends(backpack_view('blank'))
@section('content')

<nav class="navbar navbar-light bg-light">
	<form class="form-inline">
		<button class="btn btn-outline-success" type="button"><a href="{{url('admin/churchannualreport/')}}">Church Annual Report</a></button>
		<button class="btn btn-outline-success" type="button"><a href="##">Designer Report</a></button>
	</form>
	<!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<li class="active"><a href="{{url('admin/churchannualreport/')}}">Church Annual Report</a></li>
			<li class="active"><a href="##">Designer Report</a></li>
		</ul> -->
</nav>
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