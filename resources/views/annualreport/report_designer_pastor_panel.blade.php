<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Filter By</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>RC / DPW</label>
                    <select id="rc-dpw-filter-select" name="rc_dpw_id_filter" class="form-control" style="width:100%">
                        @foreach ($crud->rc_dpw as $key => $rcDpw)
                            <option value="{{$rcDpw->rc_dpw_name}}">{{$rcDpw->rc_dpw_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <select id="title-filter-select" name="title_id_filter" class="form-control" style="width:100%">
                        @foreach ($crud->title as $title)
                            <option value="{{$title->short_desc}}">{{$title->short_desc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <select id="country-filter-select" name="country_id_filter" class="form-control" style="width:100%">
                        @foreach ($crud->country as $country)
                            <option value="{{$country->country_name}}">{{$country->country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pastor Status</label>
                    <select id="pastor-status-filter-select" name="pastor_status_id_filter" class="form-control" style="width:100%">
                        @foreach ($crud->pastorStatus as $pastorStatus)
                            <option value="{{$pastorStatus->status}}">{{$pastorStatus->status}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Card</label>
                    <select id="card-filter-select" name="card_id_filter" class="form-control" style="width:100%">
                        @foreach ($crud->card as $card)
                            <option value="{{$card->card}}">{{$card->card}}</option>
                        @endforeach
                    </select>
                </div>
                <label>Filter Type</label>
                <div class="form-check mb-4 mt-2">
                <div class="form-check-inline col-sm-5">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" id="filter_type" name="filter_type" value="all" checked>All
                    </label>
                </div>
                <div class="form-check-inline col-sm-5">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" id="filter_type" name="filter_type"  value="d90">Valid by D-90
                    </label>
                </div>
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
        if (jQuery.ui) {
                var datepicker = $.fn.datepicker.noConflict();
                $.fn.bootstrapDP = datepicker;
            } else {
                $.fn.bootstrapDP = $.fn.datepicker;
            }
            var dateFormat=function(){var a=/d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,b=/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,c=/[^-+\dA-Z]/g,d=function(a,b){for(a=String(a),b=b||2;a.length<b;)a="0"+a;return a};return function(e,f,g){var h=dateFormat;if(1!=arguments.length||"[object String]"!=Object.prototype.toString.call(e)||/\d/.test(e)||(f=e,e=void 0),e=e?new Date(e):new Date,isNaN(e))throw SyntaxError("invalid date");f=String(h.masks[f]||f||h.masks.default),"UTC:"==f.slice(0,4)&&(f=f.slice(4),g=!0);var i=g?"getUTC":"get",j=e[i+"Date"](),k=e[i+"Day"](),l=e[i+"Month"](),m=e[i+"FullYear"](),n=e[i+"Hours"](),o=e[i+"Minutes"](),p=e[i+"Seconds"](),q=e[i+"Milliseconds"](),r=g?0:e.getTimezoneOffset(),s={d:j,dd:d(j),ddd:h.i18n.dayNames[k],dddd:h.i18n.dayNames[k+7],m:l+1,mm:d(l+1),mmm:h.i18n.monthNames[l],mmmm:h.i18n.monthNames[l+12],yy:String(m).slice(2),yyyy:m,h:n%12||12,hh:d(n%12||12),H:n,HH:d(n),M:o,MM:d(o),s:p,ss:d(p),l:d(q,3),L:d(q>99?Math.round(q/10):q),t:n<12?"a":"p",tt:n<12?"am":"pm",T:n<12?"A":"P",TT:n<12?"AM":"PM",Z:g?"UTC":(String(e).match(b)||[""]).pop().replace(c,""),o:(r>0?"-":"+")+d(100*Math.floor(Math.abs(r)/60)+Math.abs(r)%60,4),S:["th","st","nd","rd"][j%10>3?0:(j%100-j%10!=10)*j%10]};return f.replace(a,function(a){return a in s?s[a]:a.slice(1,a.length-1)})}}();dateFormat.masks={default:"ddd mmm dd yyyy HH:MM:ss",shortDate:"m/d/yy",mediumDate:"mmm d, yyyy",longDate:"mmmm d, yyyy",fullDate:"dddd, mmmm d, yyyy",shortTime:"h:MM TT",mediumTime:"h:MM:ss TT",longTime:"h:MM:ss TT Z",isoDate:"yyyy-mm-dd",isoTime:"HH:MM:ss",isoDateTime:"yyyy-mm-dd'T'HH:MM:ss",isoUtcDateTime:"UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"},dateFormat.i18n={dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],monthNames:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","January","February","March","April","May","June","July","August","September","October","November","December"]},Date.prototype.format=function(a,b){return dateFormat(this,a,b)};
        function initDatePickerElement(element) {
            var $fake = element,
            $field = $fake.closest('.form-group').find('input[type="hidden"]'),
            $customConfig = $.extend({
                format: 'dd/mm/yyyy'
            }, $fake.data('bs-datepicker'));
            //console.log($customConfig);
            $picker = $fake.bootstrapDP($customConfig);

            var $existingVal = $field.val();

                if( $existingVal.length ){
                    // Passing an ISO-8601 date string (YYYY-MM-DD) to the Date constructor results in
                    // varying behavior across browsers. Splitting and passing in parts of the date
                    // manually gives us more defined behavior.
                    // See https://stackoverflow.com/questions/2587345/why-does-date-parse-give-incorrect-results
                    var parts = $existingVal.split('-');
                    var year = parts[0];
                    var month = parts[1] - 1; // Date constructor expects a zero-indexed month
                    var day = parts[2];
                    preparedDate = new Date(year, month, day);
                    //$fake.val(preparedDate.format('yyyy-mm-dd'));
                    $picker.bootstrapDP('update', preparedDate);
                }

                // prevent users from typing their own date
                // since the js plugin does not support it
                // $fake.on('keydown', function(e){
                //     e.preventDefault();
                //     return false;
                // });

            $picker.on('show hide change', function(e){
                if( e.date ){
                    var sqlDate = e.format('yyyy-mm-dd');
                } else {
                    try {
                        var sqlDate = $fake.val();

                        if( $customConfig.format === 'dd/mm/yyyy' ){
                            sqlDate = new Date(sqlDate.split('/')[2], sqlDate.split('/')[1] - 1, sqlDate.split('/')[0]).format('yyyy-mm-dd');
                        }
                    } catch(e){
                        if( $fake.val() ){
                                new Noty({
                                    type: "error",
                                    text: "<strong>Whoops!</strong><br>Sorry we did not recognise that date format, please make sure it uses a yyyy mm dd combination"
                                    }).show();
                            }
                        }
                    }
                    $field.val(sqlDate);
                });
            }
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
            
            $('#rc-dpw-filter-select').select2({
                theme: "bootstrap",
                placeholder:"",
                allowClear: true,
            });
            $('#title-filter-select').select2({
                theme: "bootstrap",
                placeholder:"",
                allowClear: true
            });
            $('#country-filter-select').select2({
                theme: "bootstrap",
                placeholder:"",
                allowClear:true
            });
            $('#pastor-status-filter-select').select2({
                theme: "bootstrap",
                placeholder:"",
                allowClear:true
            });
            $('#card-filter-select').select2({
                theme: "bootstrap",
                placeholder: "",
                allowClear: true
            });

            $('#btn-search').click(function(){
                var ajax_table = $("#crudTable").DataTable();
                var current_url = ajax_table.ajax.url();
                var new_url = addOrUpdateUriParameterCustom(current_url, 'rc_dpw_id', $('#rc-dpw-filter-select').val());
                var new_url = addOrUpdateUriParameterCustom(new_url, 'title_id', $('#title-filter-select').val());
                var new_url = addOrUpdateUriParameterCustom(new_url, 'country_id', $('#country-filter-select').val());
                var new_url = addOrUpdateUriParameterCustom(new_url, 'pastor_status_id', $('#pastor-status-filter-select').val());
                var new_url = addOrUpdateUriParameterCustom(new_url, 'card_id', $('#card-filter-select').val());
                var new_url = addOrUpdateUriParameterCustom(new_url, 'filter_type', $('#filter_type:checked').val());
                new_url = normalizeAmpersandCustom(new_url.toString());
                realSearchUrl = normalizeAmpersandCustom(new_url.toString());
				ajax_table.ajax.url(new_url).load();
            });
        });
        
    </script>
@endpush
