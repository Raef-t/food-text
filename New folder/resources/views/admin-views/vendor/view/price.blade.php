@php use App\CentralLogics\CategoryLogic; @endphp
@extends('layouts.admin.app')

@section('title',$store->name."'s ".translate('messages.settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
    <div class="content container-fluid">
        @include('admin-views.vendor.view.partials._header',['store'=>$store])
        <!-- Page Heading -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="vendor">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                        <span class="card-header-icon">
                            <img class="w--22" src="{{asset('public/assets/admin/img/store.png')}}" alt="">
                        </span>
                            <span class="p-md-1"> {{translate('messages.store_price')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.item.addPriceToProducts',) }}">
                            @csrf
                            <div class="row g-3">
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.main_category')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <select id="categorySelect" name="category_id" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>{{translate('Select Main Category')}}</option>


                                        @foreach($categories as $category)
                                            @php
                                                // Get the IDs of child categories
                                               $idArray = $category->childes->pluck('id')->toArray();

                                                // Fetch the item with the highest price in the category
                                                $maxPriceItem = App\Models\Item::whereIn('category_id', $idArray)
                                                    ->orderBy('price', 'desc')
                                                    ->first();

                                                // Fetch the item with the lowest price in the category
                                                $minPriceItem = App\Models\Item::whereIn('category_id', $idArray)
                                                    ->orderBy('price', 'asc')
                                                    ->first();

                                                // Initialize price variables
                                                $minPrice = $minPriceItem ? $minPriceItem->price : 0;
                                                $maxPrice = $maxPriceItem ? $maxPriceItem->price : 0;

                                                // Initialize original price variables
                                                $minOriginal = $minPriceItem ? $minPriceItem->organal_price : 0;
                                                $maxOriginal = $maxPriceItem ? $maxPriceItem->organal_price : 0;
                                            @endphp

                                            <option value="{{ $category->id }}">
                                                {{ $category['name'] }} ({{ $minPrice  }}) - ({{ $maxPrice  }} ) ({{ $minOriginal }}) - ({{ $maxOriginal }})
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.percent')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="Percent_added" min="0" max="100" step="0.01" name="percent" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.min_Price')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="min_Price" min="0" max="10000" step="0.01" name="min_price" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.max_price')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="max_price" min="0" max="1000000" step="0.01" name="max_price" class="form-control" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-3">{{translate('messages.submit')}}</button>
                        </form>
                    </div>
                    <br>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="vendor">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <img class="w--22" src="{{asset('public/assets/admin/img/store.png')}}" alt="">
                        </span>
                                        <span class="p-md-1"> reset price</span>
                                    </h5>
                                </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.item.removePriceToProducts',) }}">
                            @csrf
                            <div class="row g-3">
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.main_category')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <select id="exampleFormControlSelect1" name="category_id" id="categorySelect"
                                            class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>{{translate('Select Main Category')}}</option>


                                        @foreach($categories as $category)
                                            @php
                                                $idArray = $category->childes->pluck('id')->toArray();

                                                if ($idArray && count($idArray) > 0) {
                                                    // Fetch the item with the highest price in the category
                                                    $maxPriceItem = App\Models\Item::whereIn('category_id', $idArray)
                                                        ->orderBy('price', 'desc')
                                                        ->first();

                                                    // Fetch the item with the lowest price in the category
                                                    $minPriceItem = App\Models\Item::whereIn('category_id', $idArray)
                                                        ->orderBy('price', 'asc')
                                                        ->first();

                                                    // Initialize price variables
                                                    $minPrice = $minPriceItem ? $minPriceItem->price : 0;
                                                    $maxPrice = $maxPriceItem ? $maxPriceItem->price : 0;

                                                    // Initialize original price variables
                                                    $minOriginal = $minPriceItem ? $minPriceItem->organal_price : 0;
                                                    $maxOriginal = $maxPriceItem ? $maxPriceItem->organal_price : 0;
                                                } else {

                                                    $maxPriceItem = App\Models\Item::where('category_id', $category->id)
                                                        ->orderBy('price', 'desc')
                                                        ->first();

                                                    // Fetch the item with the lowest price in the category
                                                    $minPriceItem = App\Models\Item::where('category_id', $category->id)
                                                        ->orderBy('price', 'asc')
                                                        ->first();

                                                    // Initialize price variables
                                                    $minPrice = $minPriceItem ? $minPriceItem->price : 0;
                                                    $maxPrice = $maxPriceItem ? $maxPriceItem->price : 0;

                                                    // Initialize original price variables
                                                    $minOriginal = $minPriceItem ? $minPriceItem->organal_price : 0;
                                                    $maxOriginal = $maxPriceItem ? $maxPriceItem->organal_price : 0;
                                                          \Log::info('Category ID Array:', ['maxPriceItem' => $maxPriceItem]);

                                                }
                                            @endphp


                                            <option value="{{ $category->id }}">
                                                {{ $category['name'] }} ({{ $minPrice  }}) - ({{ $maxPrice  }} ) ({{ $minOriginal }}) - ({{ $maxOriginal }})
                                            </option>

                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.percent')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="Percent_added" min="0" max="100" step="0.01" name="percent" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.min_Price')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="min_Price" min="0" max="10000" step="0.01" name="min_price" class="form-control" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="input-label" for="exampleFormControlSelect2">{{translate('messages.max_price')}}
                                        <span class="input-label-secondary">*</span>
                                    </label>
                                    <input type="number" id="max_price" min="0" max="1000000" step="0.01" name="max_price" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">{{translate('messages.submit')}}</button>

                        </form>
                    </div>
                                <br>
                                <div class="card-body">
                                    <table class="table table-bordered mt-3" id="itemsTable">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>السعر الاصلي </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div id="loader" style="display: none;">Loading...</div>

                            </div>

            </div>
        </div>
    </div>

    <!-- Create schedule modal -->



@endsection

@push('script_2')
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    <script>


                        $(document).ready(function () {
                            // Trigger AJAX when category is selected
                            $('#categorySelect').on('change', function () {
                                let categoryId = $(this).val();

                                if (categoryId) {
                                    $('#loader').show();

                                    $.ajax({
                                        url: `https://shalafood.net/admin/get-items/${categoryId}`,
                                        type: 'GET',
                                        success: function (response) {
                                            $('#loader').hide();

                                            // Clear the table
                                            $('#itemsTable tbody').empty();

                                            if (response.length > 0) {
                                                // Populate the table
                                                response.forEach(item => {
                                                    // Build the item view URL dynamically
                                                    const itemViewUrl = `/admin/item/view/${item.id}`;
                                                    $('#itemsTable tbody').append(`
                                <tr>
                                    <td><a href="${itemViewUrl}">${item.id}</a></td>
                                    <td>${item.name}</td>
                                    <td>${item.price}</td>
                                    <td>${item.organal_price ?? 0}</td>
                                </tr>
                            `);
                                                });
                                            } else {
                                                $('#itemsTable tbody').append(`
                            <tr>
                                <td colspan="4" class="text-center">No items found</td>
                            </tr>
                        `);
                                            }
                                        },
                                        error: function (xhr, status, error) {
                                            console.log("AJAX Error: ", status, error);
                                            console.log("Response Text: ", xhr.responseText);
                                            alert('Error fetching items. Please try again.');
                                        }
                                    });
                                } else {
                                    // Clear the table if no category is selected
                                    $('#itemsTable tbody').empty();
                                }
                            });
                        });

                    </script>

                    <!-- Page level plugins -->
    <script>
        "use strict";
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();

            $('#exampleModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let day_name = button.data('day');
                let day_id = button.data('dayid');
                let modal = $(this);
                modal.find('.modal-title').text('{{translate('messages.Create Schedule For ')}} ' + day_name);
                modal.find('.modal-body input[name=day]').val(day_id);
            })

            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });


        });


        $(document).on('click', '.delete-schedule', function () {
            let route = $(this).data('url');
            Swal.fire({
                title: '<?php echo e(translate('Want_to_delete_this_schedule?')); ?>',
                text: '<?php echo e(translate('If_you_select_Yes,_the_time_schedule_will_be_deleted')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '<?php echo e(translate('messages.no')); ?>',
                confirmButtonText: '<?php echo e(translate('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success('<?php echo e(translate('messages.Schedule removed successfully')); ?>', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error('<?php echo e(translate('messages.Schedule not found')); ?>', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                }
            })
        });

        $('#add-schedule').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.store.add-schedule')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{translate('messages.Schedule added successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
