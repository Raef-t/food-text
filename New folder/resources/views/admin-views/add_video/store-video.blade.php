@extends('layouts.admin.app')

@section('title',translate('messages.Add new category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('add_new_Video')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.video.add_video_store',)}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group col-sm-6">
                                <label class="input-label"
                                       for="exampleFormControlSelect1">{{translate('messages.select-store')}}
                                    <span class="input-label-secondary">*</span></label>
                                <select id="exampleFormControlSelect1" name="store_id" class="form-control js-select2-custom" required>
                                    <option value="" selected disabled>{{translate('select-store')}}</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store['id']}}" >{{$store['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="input-label" for="exampleFormControlInput1">link </label>
                                <input type="text" name="link" class="form-control" placeholder="{{translate('messages.link')}}" value="{{old('link')}}" maxlength="191">
                            </div>

                        </div>

                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{isset($category)?translate('messages.update'):translate('messages.add')}}</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{translate('messages.video_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">0</span></h5>

                    <!-- Unfold -->

                    <!-- End Unfold -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                           class="table table-borderless table-thead-bordered table-align-middle"
                           data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{translate('messages.id')}}</th>
                            <th class="border-0 w--1">{{translate('messages.link')}}</th>
                            <th class="border-0 w--1">{{translate('messages.store_id')}}</th>
                            <th class="border-0 text-center">{{translate('messages.status')}}</th>
                            <th class="border-0 text-center">{{translate('messages.action')}}</th>
                        </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($videos as $key=>$category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{Str::limit($category['link'], 20,'...')}}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{Str::limit($category['store_id'], 20,'...')}}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$category->id}}">
                                        <input type="checkbox" data-url="{{route('admin.video.toggle_video',['id'=>$category['id']])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$category->id}}" {{$category->	is_active?'checked':''}}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                           data-id="category-{{$category['id']}}" data-message="{{ translate('Want to delete this video') }}" title="{{translate('messages.delete_video')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.video.delete_video',['id'=>$category['id']])}}" method="post" id="category-{{$category['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if(count($videos) !== 0)
                <hr>
            @endif
            <div class="page-area">
            </div>
            @if(count($videos) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
            @endif
        </div>

    </div>

@endsection

