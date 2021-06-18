@extends(backpack_view('blank'))
@section('content')

<div class="row">
	<div class="col-md-6">

		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				Import Church
			</div>
			<div class="card-body">
				@if(Session::get('message'))
				<div id="alertStatus" class="row">
					<div class="col-md-12">
						<div class="alert alert-{{Session::get('status')}} alert-dismissible fade show" role="alert">
							{{Session::get('message')}}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				@endif
				<form id="form-upload-church" action="{{url('admin/church-upload/')}}" method="POST" enctype="multipart/form-data">
					@csrf
					
					<div class="form-group">
						<div class="file-upload btn btn-default">
							<img src="https://image.flaticon.com/icons/png/512/568/568717.png" width="64px" class="img-responsive">
							<br>
							<span class="text-upload">Drop Your File Here</span>
							<input id="file_church" class="upload rect-validation" type="file" name="file_church" style="width: 100%; height:100%;">
						</div>
						<!-- <input type="file" name="file_church" id="file_church" class="rect-validation form-control" style="height: 100px;"> -->
					</div>
					<div class="form-group">
						<button type='button' class="btn btn-primary" onclick="submitAfterValid('form-upload-church', true)">Upload</button>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
@push('after_styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{asset('css/rectstyle.css')}}">

@endpush

@push('after_scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('js/rectscript.js')}}"></script>

<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="massError-form-upload-church">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Error</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 table-responsive">
                    <table id="supportDtCust" class="table">
                        <thead>
                            <tr>
                                <th width="10%">Row</th>
                                <th>Pesan Error</th>
                            </tr>
                        </thead>
                        <tbody class="tbody-errors">
                           
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.location = '{{url('admin/import-church')}}'">Tutup</button>

            </div>
        </div>
    </div>
</div>

<script>

$('#file_church').change(function() {
  $('.text-upload').text($('#file_church')[0].files[0].name);
});
</script>
@endpush
@endsection
