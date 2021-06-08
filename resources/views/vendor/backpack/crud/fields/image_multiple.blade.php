@php
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['class'] = $field['wrapper']['class'] ?? "form-group col-sm-12";
    $tempClass = $field['wrapper']['class'];
    $field['wrapper']['class'] .= ' mb-0';
@endphp
@include('crud::fields.inc.wrapper_start')
    <div>
        <label>{!! $field['label'] !!}</label>
        @include('crud::fields.inc.translatable_icon')
    </div>
@include('crud::fields.inc.wrapper_end') 
@php
    $field['prefix'] = $field['prefix'] ?? '';
    $field['disk'] = $field['disk'] ?? null;

    if (! function_exists('getDiskUrl')) {
        function getDiskUrl($disk, $path) {
            try {
                // make sure the value don't have disk base path on it, this is the same as `Storage::disk($disk)->url($prefix);`,
                // we need this solution to deal with `S3` not supporting getting empty urls
                // that could happen when there is no $prefix set.
                $origin = substr(Storage::disk($disk)->url('/'), 0, -1);
                $path = str_replace($origin, '', $path);

                return Storage::disk($disk)->url($path);
            }
            catch (Exception $e) {
                // the driver does not support retrieving URLs (eg. SFTP)
                return url($path);
            }
        }
    }

    if (! function_exists('maximumServerUploadSizeInBytes')) {
        function maximumServerUploadSizeInBytes() {

            $val = trim(ini_get('upload_max_filesize'));
            $last = strtolower($val[strlen($val)-1]);

            switch($last) {
                // The 'G' modifier is available since PHP 5.1.0
                case 'g':
                    $val = (int)$val * 1073741824;
                    break;
                case 'm':
                    $val = (int)$val * 1048576;
                    break;
                case 'k':
                    $val = (int)$val * 1024;
                    break;
            }

            return $val;
        }
    }

    $max_image_size_in_bytes = $field['max_file_size'] ?? (int)maximumServerUploadSizeInBytes();
    $field['wrapper']['class'] = $tempClass;
    $field['wrapper']['class'] = $field['wrapper']['class'].' cropperImage cropperImageMultiple';
    $tempClass = $field['wrapper']['class'];
    $field['wrapper']['class'] .= ' d-none';
    $field['wrapper']['data-max-file-size'] = $max_image_size_in_bytes;
    $field['wrapper']['data-aspectRatio'] = $field['aspect_ratio'] ?? 0;
    $field['wrapper']['data-crop'] = $field['crop'] ?? false;
    $field['wrapper']['data-clonable'] = true;
    $field['wrapper']['data-field-name'] = ($field['wrapper']['data-field-name'] ?? $field['name']) . '_clonable';
    $field['wrapper']['data-real-field-name'] = $field['name'];
@endphp
@include('crud::fields.inc.wrapper_start')
    <div class="row head-image mb-3">
        <div class="col-sm-6">
            <small class="font-weight-bold image-label">Image Label</small>
            <input type="text" class="form-control">
            <input type="hidden" class="form-control">
        </div>
    </div>
    {{-- Wrap the image or canvas element with a block element (container) --}}
    <div class="row body-image">
        <div class="col-sm-6" data-handle="previewArea" style="margin-bottom: 20px;">
            <img data-handle="mainImage" src="">
        </div>
        @if(isset($field['crop']) && $field['crop'])
        <div class="col-sm-3" data-handle="previewArea">
            <div class="docs-preview clearfix">
                <div class="img-preview preview-lg">
                    <img src="" style="display: block; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important; margin-left: -32.875px; margin-top: -18.4922px; transform: none;">
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="btn-group footer-image">
        <div class="btn btn-light btn-sm btn-file">
            {{ trans('backpack::crud.choose_file') }} <input type="file" accept="image/*" data-handle="uploadImage"  @include('crud::fields.inc.attributes')>
            <input type="hidden" data-handle="hiddenImage" data-value-prefix="{{ $field['prefix'] }}">
            <input type="hidden">
        </div>
        @if(isset($field['crop']) && $field['crop'])
        <button class="btn btn-light btn-sm" data-handle="rotateLeft" type="button" style="display: none;"><i class="la la-rotate-left"></i></button>
        <button class="btn btn-light btn-sm" data-handle="rotateRight" type="button" style="display: none;"><i class="la la-rotate-right"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomIn" type="button" style="display: none;"><i class="la la-search-plus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomOut" type="button" style="display: none;"><i class="la la-search-minus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="reset" type="button" style="display: none;"><i class="la la-times"></i></button>
        @endif
        <button class="btn btn-light btn-sm" data-handle="remove" type="button"><i class="la la-trash"></i></button>
    </div>
    <br>
    <button class="btn btn-danger btn-sm remove-image mt-1" type="button"><i class="la la-close"></i>&nbsp; Remove Image</button>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')
@php
    $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitMultipleCropperImageElement';
    $field['wrapper']['data-field-name'] = $field['name'];
    $field['wrapper']['class'] = $tempClass;
    unset($field['wrapper']['data-clonable']);

    $oldImageSession = old('has_' .  $field['name']);
    if($oldImageSession !== null){
        $images = old($field['name']) ?? [];
        $imageLabels = old($field['name'] . '_label') ?? [];
        $imageIds = old($field['name'] . '_ids') ?? [];
        $imageChanges = old($field['name'] . '_change') ?? [];
        $validImages = [];
        foreach($images as $index => $image){
            $validImages[] = [
                'id' => $imageIds[$index] ?? '',
                'image' => $image,
                'label' => $imageLabels[$index] ?? '',
                'image_change' => $imageChanges[$index] ?? 0
            ];
        }
    } 
    else{
        $validImages = $field['value'] ?? [];
    }
@endphp
<input type="hidden" name="has_{{$field['name']}}" value="1">
@foreach ($validImages as $index => $validImage)
    @php
        $imageValue = $validImage['image'] ?? '';
        if($imageValue && !preg_match('/^data\:image\//', $imageValue)) {
            // make sure to append prefix once to value
            $imageValue = Str::start($imageValue, $field['prefix']);

            // generate URL
            $imageValue = $field['disk']
                ? getDiskUrl($field['disk'], $imageValue)
                : url($imageValue);
        }
    @endphp
    @include('crud::fields.inc.wrapper_start')
    <div class="row head-image mb-3">
        <div class="col-sm-6">
            <small class="font-weight-bold image-label">Image Label</small>
            <input type="text" class="form-control" name="{{$field['name'] . '_label[]'}}" value="{{$validImage['label'] ?? ''}}">
            <input type="hidden" class="form-control" name="{{$field['name'] . '_ids[]'}}" value="{{$validImage['id'] ?? ''}}">
        </div>
    </div>
    {{-- Wrap the image or canvas element with a block element (container) --}}
    <div class="row body-image">
        <div class="col-sm-6" data-handle="previewArea" style="margin-bottom: 20px;">
            <img data-handle="mainImage" src="">
        </div>
        @if(isset($field['crop']) && $field['crop'])
        <div class="col-sm-3" data-handle="previewArea">
            <div class="docs-preview clearfix">
                <div class="img-preview preview-lg">
                    <img src="" style="display: block; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important; margin-left: -32.875px; margin-top: -18.4922px; transform: none;">
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="btn-group">
        <div class="btn btn-light btn-sm btn-file">
            {{ trans('backpack::crud.choose_file') }} <input type="file" accept="image/*" data-handle="uploadImage"  @include('crud::fields.inc.attributes')>
            <input type="hidden" data-handle="hiddenImage" name="{{ $field['name'] }}[]" data-value-prefix="{{ $field['prefix'] }}" value="{{ $imageValue }}">
            <input type="hidden" name="{{ $field['name'] }}_change[]" value="{{$validImage['image_change'] ?? 0}}">
        </div>
        @if(isset($field['crop']) && $field['crop'])
        <button class="btn btn-light btn-sm" data-handle="rotateLeft" type="button" style="display: none;"><i class="la la-rotate-left"></i></button>
        <button class="btn btn-light btn-sm" data-handle="rotateRight" type="button" style="display: none;"><i class="la la-rotate-right"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomIn" type="button" style="display: none;"><i class="la la-search-plus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomOut" type="button" style="display: none;"><i class="la la-search-minus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="reset" type="button" style="display: none;"><i class="la la-times"></i></button>
        @endif
        <button class="btn btn-light btn-sm" data-handle="remove" type="button"><i class="la la-trash"></i></button>
    </div>
    <br>
    <button class="btn btn-danger btn-sm remove-image mt-1" type="button"><i class="la la-close"></i>&nbsp; Remove Image</button>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
    @include('crud::fields.inc.wrapper_end')
@endforeach
<div class="mx-3 mb-3">
    <button class="btn btn-primary btn-sm add-image" type="button"><i class="la la-plus"></i>&nbsp;Add Image</button>
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <link href="{{ asset('packages/cropperjs/dist/cropper.min.css') }}" rel="stylesheet" type="text/css" />
        <style>
             small:not(:empty).image-label::after {
                content: ' *';
                color: #ff0000;
            }
            .image .btn-group {
                margin-top: 10px;
            }
            img {
                max-width: 100%; /* This rule is very important, please do not ignore this! */
            }
            .img-container, .img-preview {
                width: 100%;
                text-align: center;
            }
            .img-preview {
                float: left;
                margin-right: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            .preview-lg {
                width: 263px;
                height: 148px;
            }

            .btn-file {
                position: relative;
                overflow: hidden;
            }
            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                background: white;
                cursor: inherit;
                display: block;
            }
        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script src="{{ asset('packages/cropperjs/dist/cropper.min.js') }}"></script>
        <script src="{{ asset('packages/jquery-cropper/dist/jquery-cropper.min.js') }}"></script>
        <script>
            function bpFieldInitMultipleCropperImageElement(element) {
                    // Find DOM elements under this form-group element
                    var $mainImage = element.find('[data-handle=mainImage]');
                    var $uploadImage = element.find("[data-handle=uploadImage]");
                    var $hiddenImage = element.find("[data-handle=hiddenImage]");
                    var $changeImage = $hiddenImage.next();
                    var $rotateLeft = element.find("[data-handle=rotateLeft]");
                    var $rotateRight = element.find("[data-handle=rotateRight]");
                    var $zoomIn = element.find("[data-handle=zoomIn]");
                    var $zoomOut = element.find("[data-handle=zoomOut]");
                    var $reset = element.find("[data-handle=reset]");
                    var $remove = element.find("[data-handle=remove]");
                    var $previews = element.find("[data-handle=previewArea]");
                    var $buttonRemove = element.find('button.remove-image');
                    // Options either global for all image type fields, or use 'data-*' elements for options passed in via the CRUD controller
                    var options = {
                        viewMode: 2,
                        checkOrientation: false,
                        autoCropArea: 1,
                        responsive: true,
                        preview : element.find('.img-preview'),
                        aspectRatio : element.attr('data-aspectRatio')
                    };
                    var crop = element.attr('data-crop');

                    // Hide 'Remove' button if there is no image saved
                    if (!$hiddenImage.val()){
                        $previews.hide();
                        $remove.hide();
                    }
                    // Make the main image show the image in the hidden input
                    $mainImage.attr('src', $hiddenImage.val());


                    // Only initialize cropper plugin if crop is set to true
                    if(crop){

                        $remove.click(function() {
                            $mainImage.cropper("destroy");
                            $mainImage.attr('src','');
                            $hiddenImage.val('');
                            $rotateLeft.hide();
                            $rotateRight.hide();
                            $zoomIn.hide();
                            $zoomOut.hide();
                            $reset.hide();
                            $remove.hide();
                            $previews.hide();
                            $changeImage.val(1);
                        });
                    } else {

                        $remove.click(function() {
                            $mainImage.attr('src','');
                            $hiddenImage.val('');
                            $remove.hide();
                            $previews.hide();
                            $changeImage.val(1);
                        });
                    }

                    $uploadImage.change(function() {
                        var fileReader = new FileReader(),
                                files = this.files,
                                file;

                        if (!files.length) {
                            return;
                        }
                        file = files[0];

                        const maxImageSize = element.attr('data-max-file-size');
                        if(maxImageSize > 0 && file.size > maxImageSize) {

                            alert(`Please pick an image smaller than ${maxImageSize} bytes.`);
                        } else if (/^image\/\w+$/.test(file.type)) {

                            fileReader.readAsDataURL(file);
                            fileReader.onload = function () {
                                $changeImage.val(1);
                                $uploadImage.val("");
                                $previews.show();
                                if(crop){
                                    $mainImage.cropper(options).cropper("reset", true).cropper("replace", this.result);
                                    // Override form submit to copy canvas to hidden input before submitting
                                    // update the hidden input after selecting a new item or cropping
                                    $mainImage.on('ready cropstart cropend', function() {
                                        var imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL(file.type);
                                        $hiddenImage.val(imageURL);
                                        return true;
                                    });


                                    $rotateLeft.show();
                                    $rotateRight.show();
                                    $zoomIn.show();
                                    $zoomOut.show();
                                    $reset.show();
                                    $remove.show();

                                } else {
                                    $mainImage.attr('src',this.result);
                                    $hiddenImage.val(this.result);
                                    $remove.show();
                                }
                            };
                        } else {
                            new Noty({
                                type: "error",
                                text: "<strong>Please choose an image file</strong><br>The file you've chosen does not look like an image."
                            }).show();
                        }
                    });

                    //moved the click binds outside change event, or we would register as many click events for the same amout of times
                    //we triggered the image change
                    if(crop) {
                        $rotateLeft.click(function() {
                            $mainImage.cropper("rotate", 90);
                        });

                        $rotateRight.click(function() {
                            $mainImage.cropper("rotate", -90);
                        });

                        $zoomIn.click(function() {
                            $mainImage.cropper("zoom", 0.1);
                        });

                        $zoomOut.click(function() {
                            $mainImage.cropper("zoom", -0.1);
                        });

                        $reset.click(function() {
                            $mainImage.cropper("reset");
                        });
                    }

                    $buttonRemove.click(function(){
                        $remove.trigger('click');
                        $(this).parent('div.form-group').remove();
                    });
            }
        </script>
        <script>
            $(document).ready(function(){
                $('button.add-image').click(function(){
                    var clonedImageCropper = $('div.cropperImageMultiple[data-clonable=1]').first();
                    if(clonedImageCropper.length == 1){
                        clonedImageCropper = clonedImageCropper.clone();
                        clonedImageCropper.attr('data-clonable', 0);
                        var fieldname = clonedImageCropper.attr('data-real-field-name');
                        var headImage = clonedImageCropper.find('div.head-image');
                        var inputLabel = headImage.find('input[type="text"]');
                        inputLabel.attr('name', fieldname + '_label[]');
                        var inputId = inputLabel.next();
                        inputId.attr('name', fieldname + '_ids[]');

                        var footerImage = clonedImageCropper.find('div.footer-image');
                        var inputImage = footerImage.find('input[data-handle="hiddenImage"]');
                        inputImage.attr('name', fieldname + '[]');
                        var inputImageChange = inputImage.next();
                        inputImageChange.attr('name', fieldname + '_change[]').val(1);
                        clonedImageCropper.removeClass('d-none');
                        $('div.cropperImageMultiple').last().after(clonedImageCropper);
                        bpFieldInitMultipleCropperImageElement($('div.cropperImageMultiple').last());
                    }
                });
            });
        </script>


    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
