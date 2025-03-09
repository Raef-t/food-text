@extends('layouts.admin.app')

@section('title',translate('messages.pandaItemsUpdate'))

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
                    {{translate('messages.pandaItemsUpdate')}}
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
                <form   action="{{ route('admin.item.panda_post') }}"
                                      method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="input-label"
                                   for="exampleFormControlSelect1">{{translate('messages.main_category')}}
                                <span class="input-label-secondary">*</span></label>
                            <select id="exampleFormControlSelect1" name="category_id" class="form-control js-select2-custom"
                                    required>
                                <option value="" selected disabled>{{translate('Select Main Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category['id']}}">{{$category['name']}}
                                        ({{Str::limit($category->module->module_name, 15, '...')}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="input-label"
                                   for="exampleFormControlSelect4">{{translate('stores')}}
                                <span class="input-label-secondary">*</span></label>
                            <select id="exampleFormControlSelect4" name="StoreId" class="form-control js-select2-custom"
                                    required>
                                <option value="" selected disabled>{{translate('stores')}}</option>
                                @foreach($stores as $store)
                                    <option value="{{$store['id']}}">{{$store['name']}}
                                        ({{Str::limit($store->module->module_name, 15, '...')}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input name="position" value="1" hidden>
<div class="row">
    <div class="form-group col-sm-6">
        <label class="input-label"
               for="exampleFormControlSelect2">{{translate('Select sub Category')}}
            <span class="input-label-secondary">*</span></label>
        <select id="exampleFormControlSelect2" name="subCategoryId" class="form-control js-select2-custom"
                required>
            <option value="" selected disabled>{{translate('Select sub Category')}}</option>
            @foreach($subCategory as $category)
                <option value="{{$category['id']}}">{{$category['name']}}</option>
            @endforeach
        </select>
    </div>
    <input name="position" value="1" hidden>
    <div class="form-group col-sm-6">
        <label class="input-label"
               for="exampleFormControlSelect3">{{translate('panda_category')}}
            <span class="input-label-secondary">*</span></label>
        <select id="exampleFormControlSelect3" name="PandaCategoryId" class="form-control js-select2-custom"
                required>
            <option value="" selected disabled>{{translate('panda_category')}}</option>
            <option value="311">اللحوم والدواجن</option>
            <option value="352">الفواكه و الخضروات</option>
            <option value="356">الألبان والبيض</option>
            <option value="350">الأجبان والمقبلات</option>
            <option value="349">الجبنة و اللبنة</option>
            <option value="334">تمور</option>
            <option value="354">الأسماك والمأكولات البحرية</option>
            <option value="368">الأرز والحبوب</option>
            <option value="369">الزيت و السمن</option>
            <option value="370">المعكرونة والنودلز</option>
            <option value="381">السكر والمحليات</option>
            <option value="371">الصوصات والتتبيلات</option>
            <option value="372">مكونات الطبخ</option>
            <option value="372">مكونات الطبخ</option>
            <option value="373">الفواكه المجففة والمكسرات والبذور</option>
            <option value="374">الشوربة</option>
            <option value="375">المعلبات</option>
            <option value="382">الأغذية العالمية و الأغذية الآسيوية</option>
            <option value="383">االزيتون والمخللات</option>
            <option value="378">الحلويات</option>
            <option value="380">الخبز المنزلي</option>
            <option value="376">حبوب الإفطار</option>
            <option value="377">المربى و الاطعمة القابلة للدهن</option>
            <option value="379">العسل</option>
            <option value="369">حليب طويل الأجل و مشتقاته</option>
            <option value="369">حليب طويل الأجل و مشتقاته</option>
            <option value="471">الأطعمة العضوية</option>
            <option value="457">الخبز</option>
            <option value="358">الشابورة</option>
            <option value="359">الكرواسون والمعجنات</option>
            <option value="360">الحلويات والكعك</option>
            <option value="362">الفواكه و الخضروات</option>
            <option value="364">اللحوم والدواجن</option>
            <option value="365">الأسماك والمأكولات البحرية</option>
            <option value="363">البطاطس المقلية وحلقات بصل</option>
            <option value="363">البطاطس المقلية وحلقات بصل</option>
            <option value="366">المعجنات و البيتزا والسمبوسة</option>
            <option value="367">الآيس كريم والحلويات</option>
            <option value="367">الآيس كريم والحلويات</option>
            <option value="465">الجاهز للخبز و الأكل"</option>
            <option value="465">الجاهز للخبز و الأكل"</option>
            <option value="384">المشروبات الغازية "</option>
            <option value="385">مشروبات الطاقة"</option>
            <option value="386">العصائر"</option>
            <option value="387">المياه"</option>
            <option value="388">الشاي والمشروبات العشبية "</option>
            <option value="389">القهوة"</option>
            <option value="390">مشروبات آخرى"</option>
            <option value="391">الشوكولاتة"</option>
            <option value="392">الفشار"</option>
            <option value="394">البسكويت و الكوكيز "</option>
            <option value="395">اللبان و حلوى النعناع"</option>
            <option value="396">المكسرات والفواكه المجففة "</option>
            <option value="397">الحلويات وحلويات الجيلي</option>
            <option value="398">قطع الكيك المغلفة</option>
            <option value="399">الوجبات خفيفة صحية</option>
            <option value="400">الحفائض</option>
            <option value="401">مناديل مبلله للاطفال</option>
            <option value="402">مستلزمات الأطفال</option>
            <option value="403">حليب الأطفال</option>
            <option value="428">أغذية الأطفال والوجبات الخفيفة</option>
            <option value="430">العناية بالجسم</option>
            <option value="407">العناية بالفم</option>
            <option value="408">الاستحمام والصابون</option>
            <option value="409">العناية الشخصية النسائية</option>
            <option value="410">العناية الشخصية الرجالية</option>
            <option value="330">الصيدلية</option>
            <option value="330">الصيدلية</option>
            <option value="411">مستلزمات غسيل الملابس</option>
            <option value="412">ورق المرحاض</option>
            <option value="413">لفائف المطبخ</option>
            <option value="414">المناديل</option>
            <option value="415">مستلزمات التنظيف</option>
            <option value="416">مستلزمات غسل الأطباق</option>
            <option value="417">ملطفات الجو</option>
            <option value="474">المبيدات الحشرية</option>
            <option value="418">المستلزمات المنزلية الضرورية وتخزين المواد الغذائية</option>
            <option value="420">مستلزمات تنظيف الأرضيات</option>
            <option value="421">مستلزمات الشوي</option>
            <option value="421">مستلزمات الشوي</option>
            <option value="339">الأجهزة المنزلية</option>
            <option value="422">القطط</option>
            <option value="405">العناية بالوجه و البشرة</option>
            <option value="406">العناية بالشعر</option>
            <option value="431">اكسسوارات التجميل و المكياج</option>
            <option value="475">العطور</option>

        </select>
    </div>
    <input name="position" value="1" hidden>

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

@push('script_2')
    <script>
        $(document).ready(function () {
            $('#category').change(function () {
                const categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: '/get-subcategories/' + categoryId,
                        type: 'GET',
                        success: function (data) {
                            $('#subCategory').empty();
                            $('#subCategory').append('<option value="">Select a SubCategory</option>');
                            $.each(data, function (key, value) {
                                $('#subCategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subCategory').empty();
                    $('#subCategory').append('<option value="">Select a SubCategory</option>');
                }
            });
        });
    </script>

@endpush
