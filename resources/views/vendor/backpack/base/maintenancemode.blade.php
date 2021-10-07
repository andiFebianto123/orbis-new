@extends(backpack_view('blank'))
@section('content')

<div class="row">
	<div class="col-md-6">

		<div class="card">
			<div class="card-header" style="background: #b5c7e0; font-weight:bold;">
				Maintenance Mode
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
				<form id="form-upload-church" action="{{url('admin/maintenance-mode-update/')}}" method="POST" enctype="multipart/form-data">
					@csrf
					
					<div class="form-group">
                        <label for="">Set Maintenance Mode</label>
                        <select name="maintenance_mode" class="form-control">
                            @foreach($modes as $key => $mode)
                            <option value="{{$key}}" @if($config->value == $key) selected @endif>{{$mode}}</option>
                            @endforeach
                        </select>
					</div>
					<div class="form-group">
						<button type='submit' class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>

@endsection
