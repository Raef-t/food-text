@extends('layouts.admin.app')

@section('title','Advertisement Create')


@section('advertisement')
    active
@endsection
@section('advertisement_create')
    active
@endsection


@push('css_or_js')
    <style>
        .upload-file__img img,
        .upload-file__video-preview {
            max-width: 100%;
            max-height: 140px;
            width: auto;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
        }

        .upload-file__video-preview {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .upload-file input[type="file"] {
            display: none;
        }

        .upload-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .upload-button:hover {
            background-color: #0056b3;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">


        <!-- Advertisement -->
        <h1 class="page-header-title mb-3">
            {{ translate('Create_Advertisement') }}
        </h1>
        <div class="card mb-20">
            <div class="card-body p-30">
                <form id="create-add-form"  method="POST" enctype="multipart/form-data" >
                    @csrf
                    @method("POST")
                        <div class="col-lg-10 mb-10">
                            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                <ul class="nav nav-tabs mb-3 border-0">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active"
                                           href="#"
                                           id="default-link">{{translate('messages.default')}}</a>
                                    </li>

                                    @if ($language)
                                        @foreach ($language as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link"
                                                   href="#"
                                                   id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                            </li>
                                        @endforeach
                                </ul>
                            </div>

                            <div class="lang_form" id="default-form">


                                <div class="mb-20">
                                    <label class="form-label">{{ translate('Advertisement_Title') }} ({{ translate('Default') }})</label>
                                    <input type="text" class="form-control" id="title" name="title[]"
                                           value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                           data-preview-text="preview-title">
                                </div>
                                <div class="form-floating mb-20">
                                    <label class="form-label">{{ translate('Short_Description') }} ({{ translate('Default') }})</label>
                                    <textarea class="form-control resize-none" id="description"
                                              placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                              data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                                </div>

                                <input type="hidden" name="lang[]" value="default">
                            </div>

                            @foreach ($language as $key => $lang)
                                <div class="d-none lang_form"
                                     id="{{ $lang }}-form">




                                    <div class="mb-20">
                                        <label class="form-label">{{ translate('Advertisement_Title') }}   ({{ strtoupper($lang) }})</label>
                                        <input type="text" class="form-control" id="title" name="title[]"
                                               value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                               data-preview-text="preview-title">
                                    </div>
                                    <div class="form-floating mb-20">
                                        <label class="form-label">{{ translate('Short_Description') }}   ({{ strtoupper($lang) }})</label>
                                        <textarea class="form-control resize-none" id="description"
                                                  placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                                  data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                                    </div>

                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                </div>
                            @endforeach

                            @else

                                <div class="mb-20">
                                    <label class="form-label">{{ translate('Advertisement_Title') }}</label>
                                    <input type="text" class="form-control" id="title" name="title[]"
                                           value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                           data-preview-text="preview-title">
                                </div>
                                <div class="form-floating mb-20">
                                    <label class="form-label">{{ translate('Short_Description') }}</label>
                                    <textarea class="form-control resize-none" id="description"
                                              placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                              data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                                </div>
                                <input type="hidden" name="lang[]" value="default">

                            @endif
 <label class="form-label" for="exampleFormControlSelect1">{{ translate('messages.Select_Store') }} </label>
                            <div class="mb-20">
                                <select name="store_id" id="store_id"  data-placeholder="{{ translate('messages.select_store') }}"
                                        class="js-data-example-ajax form-control">
                                </select>
                            </div>

                            <label class="form-label">{{ translate('Select_Priority') }}</label>
                            <div class="mb-20">
                                <select class="form-control w-100 js-select2-custom" name="priority">
                                    <option value="" selected="" disabled="">{{ translate('Priority') }}</option>
                                    <option value="">{{ translate('messages.N/A') }}</option>
                                    @for ($i = 1; $i <= $total_adds; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-20">
                                <label class="form-label">{{ translate('Advertisement_Type') }}</label>
                                <select class="js-select form-control w-100 promotion_type" name="advertisement_type">
                                    <option value="video_promotion">{{ translate('Video_Promotion') }}</option>
                                    <option value="store_promotion" selected="">{{ translate('store_promotion') }}</option>
                                </select>
                            </div>
                            <div class="mb-20">
                                <label class="form-label">{{ translate('Validity') }}</label>
                                <div class="position-relative">
                                    <i class="tio-calendar-month icon-absolute-on-right"></i>
                                    <input type="text" class="form-control h-45 position-relative bg-transparent"  name="dates" placeholder="{{ translate('messages.Select_Date') }}">
                                </div>
                            </div>


                            <div class="promotion-typewise-upload-box" id="video-upload-box">
                                <label class="form-label">{{ translate('Upload Related Files') }}</label>
                                <div class="border rounded p-3">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <p class="title-color mb-0">{{ translate('Upload Your Video') }} ({{ translate('16:9') }})</p>
                                        <div class="upload-file">
                                            <input type="file" id="videoFileInput" class="video_attachment" name="video_attachment" accept="video/mp4, video/webm, video/mkv" onchange="previewVideo(event)">
                                            <label for="videoFileInput" class="upload-button">Select Video</label>
                                            <video id="videoPreview" class="upload-file__video-preview" controls></video>
                                            <button class="remove-file-button" type="button" onclick="clearVideoPreview()">
                                                <i class="tio-clear"></i>
                                            </button>
                                        </div>
                                        <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                            {{ translate('Maximum 5 MB') }}<br>
                                            {{ translate('Supports: MP4, WEBM, MKV') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="promotion-typewise-upload-box" id="profile-upload-box">
                                <h5 class="mb-3">{{ translate('Show Review') }} &amp; {{ translate('Ratings') }}</h5>
                                <div class="card bg--secondary shadow-none">
                                    <div class="card-body p-3">
                                        <div class="w-100 d-flex flex-wrap gap-3">
                                            <label class="form-check form--check-2 me-3">
                                                <input type="checkbox" id="is_review_checked" class="form-check-input" value="1" name="review" checked="">
                                                <span class="form-check-label">{{ translate('Review') }}</span>
                                            </label>
                                            <label class="form-check form--check-2">
                                                <input type="checkbox" id="is_rating_checked" class="form-check-input" value="1" name="rating" checked="">
                                                <span class="form-check-label">{{ translate('Rating') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-label">{{ translate('Upload Related Files') }}</label>
                                <div class="d-flex flex-wrap flex-sm-nowrap justify-content-center gap-3 border rounded p-3">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <p class="title-color mb-0">{{ translate('Profile Image') }} <span class="text-danger">({{ translate('Ratio - 1:1') }})</span></p>
                                        <div class="upload-file">
                                            <input type="file" id="profileImageInput" class="cover_attachment js-upload-input" data-target="profile-prev-image" name="profile_image" accept=".png,.jpg,.jpeg,.gif, |image/*">
                                            <label for="profileImageInput" class="upload-button">Select Image</label>
                                            <div class="upload-file__img">
                                                <img src="{{asset('public/assets/admin/img/media/upload-file.png')}}" alt="">
                                            </div>
                                            <button class="remove-file-button" type="button">
                                                <i class="tio-clear"></i>
                                            </button>
                                        </div>
                                        <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                            {{ translate('Supports: PNG, JPG, JPEG, WEBP') }}<br>
                                            {{ translate('Maximum 2 MB') }}
                                        </p>
                                    </div>
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <p class="title-color mb-0">{{ translate('Upload Cover') }} <span class="text-danger">({{ translate('Ratio - 2:1') }})</span></p>
                                        <div class="upload-file">
                                            <input type="file" id="coverImageInput" class="cover_attachment js-upload-input" data-target="main-image" name="cover_image" accept=".png,.jpg,.jpeg,.gif, |image/*">
                                            <label for="coverImageInput" class="upload-button">Select Cover</label>
                                            <div class="upload-file__img upload-file__img_banner">
                                                <img src="{{asset('public/assets/admin/img/media/banner-upload-file.png')}}" alt="">
                                            </div>
                                            <button class="remove-file-button" type="button">
                                                <i class="tio-clear"></i>
                                            </button>
                                        </div>
                                        <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                            {{ translate('Supports: PNG, JPG, JPEG, WEBP') }}<br>
                                            {{ translate('Maximum 2 MB') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('Reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Advertisement -->

    </div>
@endsection

@push('script_2')

    <script type="text/javascript" src="{{asset('public/assets/admin/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('public/assets/admin/js/daterangepicker.min.js')}}"></script>
    <script>
        function previewVideo(event) {
            const file = event.target.files[0];
            const videoPreview = document.getElementById('videoPreview');

            if (file) {
                const fileURL = URL.createObjectURL(file);
                videoPreview.src = fileURL;
                videoPreview.style.display = 'block';
            }
        }

        function clearVideoPreview() {
            const videoInput = document.getElementById('videoFileInput');
            const videoPreview = document.getElementById('videoPreview');

            videoInput.value = '';
            videoPreview.src = '';
            videoPreview.style.display = 'none';
        }
    </script>
    <script>
        $(function () {
            $('input[name="dates"]').daterangepicker({
                // timePicker: true,
                minDate: new Date(),
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(10, 'day'),
            });

            $('.js-select').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>


    <!-- Video Upload Handlr -->
    <script>
        $(".video_attachment").on("change", function (event) {
            const videoEl = $(".video > video")
            const prevVideoBox = $('.prev-video-box')
            let file = event.target.files[0];
            let blobURL = URL.createObjectURL(file);
            const prevImage = $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src');
            videoEl.css('display', 'block');
            videoEl.attr('src', blobURL);
            videoEl.siblings('.play-icon').hide();
            $(this).closest('.upload-file').find('.upload-file__img').html('<video src="' + blobURL + '" controls></video>');
            $(this).closest('.upload-file').find('.remove-file-button').show()
            $(this).closest('.upload-file').find('.remove-file-button').on('click', function () {
                $(this).hide()
                videoEl.siblings('.play-icon').show();
                $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src', prevImage);
                $(this).closest('.upload-file').find('.video_attachment').val('');
                $(this).closest('.upload-file').find('.video > video').css('display', 'none');
                videoEl.css('display', 'none');
                videoEl.attr('src', '');
            })
        })

        $(window).on('load', function () {
            handleUploadBox();

            const videoEl = $(".video > video")
            let blobURL = "";
            // prev video attachment file
            {{-- blobURL = "{{asset('storage/app/public/advertisement').'/' . $advertisement?->attachment?->file_name}}"; --}}

            videoEl.css('display', 'block');
            videoEl.attr('src', blobURL);
            $(".video_attachment").closest('.upload-file').find('.upload-file__img').html('<video src="' + blobURL + '" controls></video>');
            $(".video_attachment").closest('.upload-file').find('.remove-file-button').show()
            $(".video_attachment").closest('.upload-file').find('.remove-file-button').on('click', function () {
                $(this).hide()
                $(this).closest('.upload-file').find('.upload-file__img').html('<img src="{{asset('public/assets/admin/img/media/video-banner.png')}}" alt="">');
                $(this).closest('.upload-file').find('.video_attachment').val('');
                $(this).closest('.upload-file').find('.video > video').css('display', 'none');
                videoEl.css('display', 'none');
                videoEl.attr('src', '');
            })
        })
    </script>

    <!-- Select Toggler Scripts -->
    <script>
        const handleUploadBox = () => {
            const value = $('.promotion_type').val();
            if (value == 'video_promotion') {
                $('#video-upload-box, #video-preview-box').show();
                $('#profile-upload-box, #profile-preview-box').hide();
            } else {
                $('#video-upload-box, #video-preview-box').hide();
                $('#profile-upload-box, #profile-preview-box').show();
            }
        }
        $(window).on('load', function () {
            handleUploadBox()
        })

        $('.promotion_type').on('change', function () {
            handleUploadBox();
            $('.remove-file-button').click()
        })
    </script>

    <!-- Profile Promotion Image Upload Handlr -->
    <script>
        $(".js-upload-input").on("change", function (event) {
            let file = event.target.files[0];
            const target = $(this).data('target');
            let blobURL = URL.createObjectURL(file);
            const prevImage = $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src');
            $(this).closest('.upload-file').find('.upload-file__img').html('<img src="' + blobURL + '" alt="">');
            $(this).closest('.upload-file').find('.remove-file-button').show()
            $('#profile-preview-box').find('.' + target).css('background', 'url(' + blobURL + ') no-repeat center center / cover');
            $(this).closest('.upload-file').find('.remove-file-button').on('click', function () {
                $('#profile-preview-box').find('.' + target).css('background', 'rgba(117, 133, 144, 0.1)');
                $(this).hide();
                $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src', prevImage);
                file ? $(this).closest('.upload-file').find('.js-upload-input').val(file) : ''
            })
        })
    </script>

    <!-- Title and Description Change Handlr -->
    <script>
        $('[data-preview-text]').on('input', function (event) {
            const target = $(this).data('preview-text');
            if (event.target.value) {
                $('.' + target).each(function () {
                    $(this).find('.main-text').text(event.target.value)
                    $(this).find('.placeholder-text').hide()
                    $(this).find('.static-text').show()
                })
            } else {
                $('.' + target).each(function () {
                    $(this).find('.main-text').text('')
                    $(this).find('.placeholder-text').show()
                    $(this).find('.static-text').hide()
                })
            }
        })
        const resetTextHandlr = () => {
            $('[data-preview-text]').each(function () {
                const target = $(this).data('preview-text');
                const value = $(this).val()
                if (value) {
                    $('.' + target).each(function () {
                        $(this).find('.main-text').text(value)
                        $(this).find('.placeholder-text').hide()
                        $(this).find('.static-text').show()
                    })
                }
            })
        }
        $(window).on('load', function () {
            resetTextHandlr()
        })

        $('#create-add-form').on('reset', function () {
            window.location.reload()
        })
    </script>

    <!-- Review and Rating Handlr -->
    <script>
        $('[name="review"]').on('change', function () {
            if ($(this).is(':checked')) {
                $('.review-placeholder').hide()
                $('.review--text').show()
                $('.review-rating-demo').css('opacity', '1')
            } else {
                $('.review-placeholder').show()
                $('.review--text').hide()
                if (!$('[name="rating"]').is(':checked')) {
                    $('.review-rating-demo').css('opacity', '0')
                }
            }
        })
        $('[name="rating"]').on('change', function () {
            if ($(this).is(':checked')) {
                $('.rating-text').show()
                $('.review-rating-demo').css('opacity', '1')
            } else {
                $('.rating-text').hide()
                if (!$('[name="review"]').is(':checked')) {
                    $('.review-rating-demo').css('opacity', '0')
                }
            }
        })


        $(window).on('load', function () {
            $('[name="review"]').each(function () {
                if ($(this).is(':checked')) {
                    $('.review--text').show()
                } else {
                    $('.review--text').hide()
                    if (!$('[name="rating"]').is(':checked')) {
                        $('.review-rating-demo').css('opacity', '0')
                    }
                }
            })
            $('[name="rating"]').each(function () {
                if ($(this).is(':checked')) {
                    $('.rating-text').show()
                } else {
                    $('.rating-text').hide()
                    if (!$('[name="review"]').is(':checked')) {
                        $('.review-rating-demo').css('opacity', '0')
                    }
                }
            })
        })
    </script>

    <script>
        $(document).on('ready', function () {
            $('.js-data-example-ajax').select2({

                ajax: {
                    url: '{{ url('/') }}/admin/store/get-stores',
                    data: function (params) {

                        return {
                            q: params.term, // search term
                            page: params.page,
                            module_id: {{ Config::get('module.current_module_id') }}
                        };
                    },
                    processResults: function (data) {
                        console.log('Request Data for processResults:', data); // Log the request data

                        return {
                            results: data
                        };
                    },
                    __port: function (params, success, failure) {
                        let $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);
                        console.log('Request Data for __port:', params); // Log the request data
                        console.log('Request Data for __port2:', success); // Log the request data
                        console.log('Request Data for __port3:', failure); // Log the request data

                        return $request;
                    }
                }
            });


            $('#create-add-form').on('submit', function (event) {
                event.preventDefault();
                let formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.advertisement.store') }}',
                    data: $('#create-add-form').serialize(),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        $('#loading').hide();

                        if (data.errors) {
                            for (let i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success(data.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            setTimeout(function () {
                                location.href = '{{route('admin.advertisement.index')}}';
                            }, 2000);
                        }
                    }
                });
            });
        });


        $(document).on('change', '.js-data-example-ajax', function () {
            var store_id = $(this).val();
            check_review_and_rating(store_id)
        });
        $(document).on('change', '#is_review_checked', function () {

            if ($(this).is(':checked') == true) {
                var store_id = $('.js-data-example-ajax').val();
                if (store_id) {
                    check_review_and_rating(store_id)
                }
            }

        });
        $(document).on('change', '#is_rating_checked', function () {

            if ($(this).is(':checked') == true) {
                var store_id = $('.js-data-example-ajax').val();
                if (store_id) {
                    check_review_and_rating(store_id)
                }
            }
        });


        function check_review_and_rating(store_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.store.get-store-ratings')}}",
                method: 'get',
                data: {
                    store_id: store_id,
                },
                beforeSend: function () {

                },
                success: function (response) {
                    $('#rating_data').html(response.rating);
                    $('#review_data').html(' (' + response.review + '+)');

                },
                complete: function () {
                },
            });
        }

    </script>

@endpush
