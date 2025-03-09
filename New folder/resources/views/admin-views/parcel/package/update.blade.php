@extends('layouts.admin.app')
@section('title',translate('messages.package_setup'))

@section('content')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.parcel.package.update', ['package' => $package->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                        @php($language = $language->value ?? null)
                        @php($defaultLang = str_replace('_', '-', app()->getLocale()))
                    
                        <div class="col-md-6">
                           
                                <div id="default-form">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{ translate('messages.default') }})</label>
                                        <input type="text" name="name" class="form-control" placeholder="{{translate('messages.new_item')}}" value="{{ old('name', $package->name) }}" required>
                                    </div>

                                </div>
                            <div id="default-form">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.english_name')}} ({{ translate('messages.default') }})</label>
                                    <input type="text" name="en_name" class="form-control" placeholder="{{translate('messages.new_item')}}" required>
                                </div>

                            </div>
                            {{-- <div class="form-group mb-0">
                                <label class="input-label">{{translate('messages.module')}}</label>
                                <select name="module_id" id="module_id" required class="form-control js-select2-custom"  data-placeholder="{{translate('messages.select_module')}}">
                                        <option value="" selected disabled>{{translate('messages.select_module')}}</option>
                                    @foreach(\App\Models\Module::parcel()->get() as $module)
                                        <option value="{{$module->id}}" >{{$module->module_name}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <input name="position" value="0" class="initial-hidden">
                        </div>
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="input-label text-capitalize">{{translate('messages.per_km_shipping_charge')}}</label>
                                <input type="number" step=".01" min="0" placeholder="{{translate('messages.per_km_shipping_charge')}}" class="form-control" name="km"{{ old('km', $package->km) }}"">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label text-capitalize">{{translate('messages.price')}}</label>
                                <input type="number" step=".01" min="0" placeholder="{{translate('messages.price')}}" class="form-control" name="price" value="{{ old('price', $package->price) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.update package')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

@endsection