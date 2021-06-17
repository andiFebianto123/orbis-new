
@push('after_styles')
<style>
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url('http://asy-syifaa.com/images/ajax-loader.gif')  center no-repeat;
    }
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
</style>
@endpush

@push('after_scripts')
<script>
    function exportReport(){
        var arrayColumnVisibility = [];
        var actualMenuType = "";

        $.urlParam = function(name,url){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
            return results != null ? results[1] : results;
        }

        $.urlTypeDesignerCheck = function(url){
            return realSearchUrl.match(/church*/g) != null ? 'church' : (realSearchUrl.match(/pastor*/g) != null ? 'pastor' : (realSearchUrl.match(/quick*/g) != null ? 'quick' : ''))
        }

        $.replaceAllUrlComponent = function(url){
            return decodeURIComponent(url).replace(/\+/g,' ');
        }

        if(typeof realSearchUrl !== 'undefined' && realSearchUrl != null){
            actualMenuType = $.urlTypeDesignerCheck(realSearchUrl);
        }

        $('.toggle-btn').each(function(){
            if($(this).hasClass('active')){
                arrayColumnVisibility.push($(this).data('column'));
            }
        }); 

        $.ajax({
            xhrFields: {
                responseType: 'blob',
            },
            type: 'POST',
            url: '{{backpack_url($crud->routeExport) . "/export-report"}}',
            data: {
                visible_column: arrayColumnVisibility,
                rc_dpw_id: actualMenuType != '' ?  $.replaceAllUrlComponent($.urlParam('rc_dpw_id', realSearchUrl)) : null,
                church_type_id: actualMenuType != '' && actualMenuType == 'church'?  $.replaceAllUrlComponent($.urlParam('church_type_id', realSearchUrl)) : null,
                country_id: actualMenuType != '' ?  $.replaceAllUrlComponent($.urlParam('country_id', realSearchUrl)) : null,
                church_status_id: actualMenuType != '' && actualMenuType == 'church'?  $.replaceAllUrlComponent($.urlParam('church_status_id', realSearchUrl)) : null,
                title_id: actualMenuType != '' && actualMenuType == 'pastor'? $.replaceAllUrlComponent($.urlParam('title_id', realSearchUrl)) : null,
                pastor_status_id: actualMenuType != '' && actualMenuType == 'pastor'? $.replaceAllUrlComponent($.urlParam('pastor_status_id', realSearchUrl)) : null,
                card_id: actualMenuType != '' && actualMenuType == 'pastor'? $.replaceAllUrlComponent($.urlParam('card_id', realSearchUrl)) : null,
                filter_type: actualMenuType != '' && actualMenuType == 'pastor'? $.replaceAllUrlComponent($.urlParam('filter_type', realSearchUrl)) : null,
                report_type: actualMenuType != '' && actualMenuType == 'quick' ? $('#report-type-select').val() : null
            },
            beforeSend: function(){
                $("body").addClass("loading"); 
            },
            complete: function(){
                $("body").removeClass("loading"); 
            },
            success: function(result, status, xhr) {

                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : null);

                if(filename != null){
                    // The actual download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                }
                else{
                
                }
                
            }
        });

    }
</script>
@endpush