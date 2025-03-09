@extends('layouts.admin.app')

@section('title',$store->name."'s ".translate('messages.transactions'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        @include('admin-views.vendor.view.partials._header',['store'=>$store])

        <div class="card px-5 py-5">
            <h2 class="text-center">{{translate("messages.reports")}}</h2>
            <form action="{{route("admin.sendReport")}}" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="input-label">{{translate('messages.title')}}
                    </label>
                    <input name="title" class="form-control"
                           required>
                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.description')}}
                    </label>
                    <input name="description" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.status')}}</label>

                    <select name="status" class="form-control" required>
                        <option value="notConnectYet" selected>{{translate('messages.notConnectYet')}}</option>
                        <option value="noConnection">{{translate('messages.noConnection')}}</option>
                        <option value="connectWithEmpo">{{translate('messages.connectWithEmpo')}}</option>
                        <option value="connectAgain">{{translate('messages.connectAgain')}}</option>
                        <option value="reviewContract">{{translate('messages.reviewContract')}}</option>
                        <option value="complet">{{translate('messages.complet')}}</option>
                        <option value="rejected">{{translate('messages.rejected')}}</option>
                        <option value="toReview">{{translate('messages.toReview')}}</option>
                        <option value="other">{{translate('messages.other')}}</option>

                    </select>
                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.way')}}</label>

                    <select name="way" class="form-control" required>
                        <option value="withPhone" selected>{{translate('messages.withPhone')}}</option>
                        <option value="whatsApp" selected>{{translate('messages.whatsApp')}}</option>
                        <option value="inSite" selected>{{translate('messages.inSite')}}</option>
                        <option value="online" selected>{{translate('messages.onlineMeeting')}}</option>
                    </select>
                </div>
                <div class="row">
                    <div class="form-group ">
                        <label class="input-label">{{translate('messages.remember')}}</label>

                        <input type="date" name="remember" class="form-control" required></input>
                    </div>


                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.files')}}</label>

                  <input type="file" name="files[]" multiple></input>
                </div>
                <input value="{{$store->id}}" name="store_id" type="hidden">
                <button type="submit" class="btn btn-primary">{{translate('messages.save')}}</button>
            </form>
        </div>
    </div>
    <div class="card px-5 py-5">
        <div class="table-responsive datatable-custom">
            <table id="columnSearchDatatable"
                   class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                   data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false

                        }'>
                <thead class="thead-light">
                <tr>
                    <th class="border-0">{{translate('sl')}}</th>
                    <th class="border-0">{{translate('messages.title')}}</th>
                    <th class="border-0">{{translate('messages.Description')}}</th>
                    <th class="border-0">{{translate('messages.replay')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.status')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.way')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.remember')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.connectDate')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.files')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.admin')}}</th>

                </tr>
                </thead>

                <tbody id="set-rows">
                @foreach($reports as $key=>$store)
                    <tr>
                        <td>{{$store->id}}</td>
                        <td>
                            <h4>{{$store->title}}</h4>
                        </td>
                        <td>
                            <h4>{{$store->description}}</h4>

                        </td>
                        <td>
                            @if(auth('admin')->user()->role_id != 3)
                            <form action="{{route('admin.sendReportReplay',["reports"=>$store])}}" method="post" class="flex w-90 items-center">
                                @csrf
                                <div class="row">
                                    <input type="text"
                                           name="replay"
                                           value="{{$store->replay??null}}"
                                           class="w-full rounded-lg border border-[#DDE2E4] px-3 py-2 text-sm"
                                           placeholder="{{translate('messages.enter your replay')}}">
                                    <button type="submit" style="border: none; background-color: transparent" >
                                        <a class="btn action-btn btn--primary float-right mr-2" data-toggle="tooltip" data-placement="top" style="border: none">
                                            <i class="tio-done font-weight-bold"></i>
                                        </a>
                                    </button>
                                </div>

                            </form>
                            @else
                            <h3>{{$store->replay??translate("messages.not_found")}}</h3>
                            @endif
                        </td>
                        <td>
                            <h3>{{translate("messages.".$store->status)??translate("messages.not_found")}}</h3>
                        </td>
                        <td>
                            <h5>{{translate("messages.".$store->way)??translate("messages.not_found")}}</h5>
                        </td>
                        <td>
                            <h5>{{$store->remember}}</h5>
                        </td>
                        <td>
                            <h5>{{$store->connectDate}}</h5>
                        </td>
                        <td>
                            <a href="{{ route("admin.downloadFiles",["Report"=>$store]) }}" target="_blank">Download files</a>
                        </td>
                        <td>
{{$store->role->f_name}} {{$store->role->l_name}}
                        </td>

                     
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        "use strict";
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
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
    </script>
@endpush
