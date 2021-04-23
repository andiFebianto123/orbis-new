<form method= 'POST' action="{{url('admin/country-upload/')}}" class="btn btn-primary" enctype="multipart/form-data">
	@csrf
	<input type="file" name="fileToUpload" id="fileToUpload">
	<button type='submit'>Upload</button>
</form>