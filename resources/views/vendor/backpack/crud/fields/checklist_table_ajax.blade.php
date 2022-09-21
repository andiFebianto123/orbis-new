<div class="form-group col-sm-12 field-{{$field['name']}}">
    <label>{!! $field['label'] !!}</label>
    <div class="">
        <table id="{{$field['name']}}" class="table table-stripped checklist-table" style="width:100%;">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="select_all" value="1" id="{{$field['name']}}-select-all">
                    </th>
                    @foreach($field['table']['table_header'] as $key1 => $col_header)
                    <th class="text-nowrap">
                        {{$col_header}}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody> </tbody>
        </table>
        <div class="section-hidden"></div>
    </div>
</div>
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp
    @push('crud_fields_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    @endpush
    @push('crud_fields_scripts')
    <script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader-bs4/js/fixedHeader.bootstrap4.min.js')}}"></script>
        <script>
            $(document).ready( function () {
                var filterDate = false
                var rowsSelected = stringToArray()
                var clName = "{{$field['name']}}"
                var table = $("#"+clName).DataTable( {
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    scrollX: true,
                    ajax: {
                        url: "{{$field['ajax_url']}}",
                        dataSrc: 'data',
                        data: function(data){}
                    },
                    "order":[[1,'asc']],
                    'columnDefs': [{
                        'targets': 0,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'checkboxes': true,
                        'render': function (data, type, full, meta){
                            return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                        }
                        },{
                            'targets': 1,
                            'className': 'text-nowrap',
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex){
                        var rowId = data[0];
                        if($.inArray(rowId, rowsSelected) !== -1){
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                } );

                $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
                    if(this.checked){
                        $("#"+clName+" tbody input[type='checkbox']:not(:checked)").trigger('click');
                    } else {
                        $("#"+clName+" tbody input[type='checkbox']:checked").trigger('click');
                    }

                    e.stopPropagation();
                });


                $("#"+clName+" tbody").on('click', 'input[type="checkbox"]', function(e){
                    var $row = $(this).closest('tr');
                    var data = table.row($row).data();
                    var rowId = data[0];
                    var index = $.inArray(rowId, rowsSelected);
                    var hiddenHtml = ""

                    if(this.checked && index === -1){
                        rowsSelected.push(rowId);
                    } else if (!this.checked && index !== -1){
                        rowsSelected.splice(index, 1);
                    }

                    $.each(rowsSelected, function( index, value ) {
                        hiddenHtml += "<input type='hidden' name='"+clName+"[]' value='"+value+"'>"
                    });

                    $(".section-hidden").html(hiddenHtml)

                    if(this.checked){
                        $row.addClass('selected');
                    } else {
                        $row.removeClass('selected');
                    }
                    e.stopPropagation();
                });

            });

            function stringToArray()
            {
                var arrCollected = []
                var strContent = "{{ $crud->entry->{$field['name']} ?? '[]' }}"
                    strContent = strContent.replace("[", "").replace("]", "").replaceAll("&quot;", "")
                    if (strContent.includes(",")) {
                        var items = strContent.split(',')
                        for (var i = 0; i < items.length; i++) {
                            arrCollected.push(parseInt(items[i]))
                        }
                    }
                    
                return arrCollected
            }
        </script>
    @endpush
@endif
