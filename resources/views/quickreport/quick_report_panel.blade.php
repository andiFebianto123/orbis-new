<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Report Type</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Type Report</label>
                    <select id="report-type-select" name="report_type_filter" class="form-control" style="width:100%">
                        @foreach ($crud->type_report as $key => $typeReport)
                            <option value="{{$key}}">{{$typeReport}}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" id="btn-search"><i class="la la-search"></i>&nbsp;Search</button>
            </div>
        </div>
    </div>
</div>
@push('after_styles')
<link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

@endpush
@push('after_scripts')
<script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('packages/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.2/URI.min.js" type="text/javascript"></script>

<script>
        var realSearchUrl = null;
        function normalizeAmpersandCustom(string) {
            return string.replace(/&amp;/g, "&").replace(/amp%3B/g, "");
        }
        function addOrUpdateUriParameterCustom(uri, parameter, value) {
            var new_url = normalizeAmpersandCustom(uri);
            new_url = URI(new_url).normalizeQuery();

            // this param is only needed in datatables persistent url redirector
            // not when applying filters so we remove it.
            if (new_url.hasQuery('persistent-table')) {
                new_url.removeQuery('persistent-table');
            }

            if (new_url.hasQuery(parameter)) {
              new_url.removeQuery(parameter);
            }

            if (value != '') {
              new_url = new_url.addQuery(parameter, value);
            }
            return new_url.toString();
        }

        
        $(document).ready(function(){
            $.urlParam = function(sParam){
                var sPageURL = window.location.search.substring(1),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
                return false;

            }
            var currentData = $.urlParam('report_type');
            
            $('#report-type-select').select2({
                theme: "bootstrap",
                placeholder:"",
            });

            if(currentData != false){
                $("#report-type-select").val(currentData).trigger('change');
            }   

            $('#btn-search').click(function(){
                var current_url = '{{url($crud->route)}}';
                var new_url = addOrUpdateUriParameterCustom(current_url, 'report_type', $('#report-type-select').val());
                window.location.replace(new_url);
            });

            realSearchUrl = '{{url($crud->route)}}';
        });
        
    </script>
@endpush
