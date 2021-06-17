function submitAfterValid(formId, massError = false) {
    $('.btn').attr('disabled', 'disabled')
    var datastring = $("#"+formId).serialize()
    var formData = new FormData($("#"+formId)[0]);

    var url = $("#"+formId).attr('action')
    
    $('.rect-validation').css({ "border": "1px solid #428fc7" })
    $('.error-message').remove()
    $(".progress-loading").remove()

    $.ajax({
        type: "POST",
        url: url,
        data : formData,
        contentType : false,
        processData : false,
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            $("#"+formId).append("<div class='progress-loading' style='margin-top:10px;text-align:center;'></div>")
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);
                var htmlProgress = "<p>Processing...</p><img src='https://asy-syifaa.com/images/ajax-loader.gif' style='height: 12px;width: 100%;'>"
                // var htmlProgress = "<span>Processing... ("+percentComplete+"%)</span><div class='rect-progressbar' style='width:"+percentComplete+"%;'></div>"
                $('.progress-loading').html(htmlProgress)
                
                if (percentComplete === 100) {
        
                }
        
              }
            }, false);
        
            return xhr;
        },
        success: function(response) {
            $('.btn').removeAttr('disabled')
            $(".progress-loading").remove()
            if (response.status) {
                window.location.href = response.redirect_to
            } else {
                messageErrorGeneral("#"+formId, response.message)
                if (massError && response.mass_errors) {
                    $(".modal").modal('hide')
                    $("#massError-"+formId).modal('show')
                    var htmlTable = ""
                    $.each(response.mass_errors, function( index, error ) {
                        htmlTable += "<tr><td>"+error.row+"</td><td>"+error.errormsg[0]+"</td></tr>"
                    });
                    $(".tbody-errors").html(htmlTable)
                    $('#supportDtCust').DataTable();
                }else{
                    $.each(response.validation_errors, function( index, error ) {
                        var currentID = $("#"+error.id)
                        $(currentID).css({ "border": "1px solid #c74266" })
                        messageErrorForm(currentID, error.message)
                    });
                }
                
            }
        },
        error: function(xhr, status, error) {
            $('.btn').removeAttr('disabled')
            $(".progress-loading").remove()
            var messageErr = error.message
            if (error.message == undefined) {
                messageErr = "Something went wrong"
            }
            messageErrorGeneral("#"+formId, messageErr)
        }
    });
}

function messageErrorForm(currentID, message) {
    $("<div class='error-message' style='color:#c74266; float:right; font-size:12px;'>" + message + "</div>")
                        .insertBefore(currentID).hide().show('medium')
}

function messageErrorGeneral(currentID, message) {
    $("<div class='error-message alert alert-danger'>" +message + "</div>")
                        .insertBefore(currentID).hide().show('medium')
}