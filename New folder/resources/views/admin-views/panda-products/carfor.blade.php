@extends('layouts.admin.app')

@section('title',translate('messages.carrefourEdit'))

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
                    {{translate('messages.carrefourEdit')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form   action="{{ route('admin.item.carrefourEdit') }}"
                        method="POST">
                    @csrf
                    <div class="form-group col-sm-6">
                        <label class="input-label"
                               for="exampleFormControlSelect3">{{translate('carrefour_category')}}
                            <span class="input-label-secondary">*</span></label>
                        <select id="exampleFormControlSelect3" name="CategoryId" class="form-control js-select2-custom"
                                required>
                            <option value="" selected disabled>{{translate('carrefour_category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-sm-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" id="reset_btn"
                                    class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

@endsection

