@extends('layouts.admin.app')

@section('title',"tasks")

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    @if(Auth("admin")->user()->role_id!=3)
    <div class="content container-fluid">
        <div class="card px-5 py-5">
            <h2 class="text-center">{{translate("messages.AddTask")}}</h2>
            <form action="{{route("admin.index-store")}}" method="POST"  enctype="multipart/form-data">
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
                    <label class="input-label">{{translate('messages.priority')}}</label>

                    <select name="priority" class="form-control" required>
                        <option value="high" selected>{{translate('messages.high')}}</option>
                        <option value="medium">{{translate('messages.medium')}}</option>
                        <option value="low">{{translate('messages.low')}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.type')}}</label>
                    <select name="type" class="form-control" required>
                        <option value="add_task" selected>{{translate('messages.add_task')}}</option>
                        <option value="add_store">{{translate('messages.add_store')}}</option>
                        <option value="update_store">{{translate('messages.update_store')}}</option>
                    </select>
                </div>
                <div class="form-group" id="store" >
                    <label class="input-label">{{translate('messages.store')}}</label>
                    <select name="store" class="form-control" >
              @foreach($stores as $store)
                            <option value="{{$store->id}}" selected>{{$store->name}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="storesManges" >
                    <label class="input-label">{{translate('messages.store')}}</label>
                    <select name="storesManges" class="form-control" >
              @foreach($storesManges as $store)

                            <option value="{{$store->id}}" selected>{{$store->name}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="input-label">{{translate('messages.assigned_to')}}</label>

                    <select name="assigned_to" class="form-control" required>
                        @foreach($admins as $admin)
                        <option value="{{$admin->id}}">{{$admin->f_name}} {{$admin->l_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group ">
                    <label class="input-label">{{translate('messages.due_date')}}</label>

                    <input type="date" name="due_date" class="form-control" required></input>
                </div>
                <button type="submit" class="btn btn-primary">{{translate('messages.save')}}</button>
            </form>
        </div>
    </div>
    @endif
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
                    <th class="border-0">{{translate('messages.status')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.priority')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.due_date')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.assigned_by')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.assigned_to')}}</th>

                </tr>
                </thead>

                <tbody id="set-rows">
                @foreach($tasks as $key=>$store)
                    <tr>
                        <td>{{$store->id}}</td>
                        <td>
                            <h4>{{$store->title}}</h4>
                        </td>
                        <td>
                            <h4>{{$store->description}}</h4>

                        </td>
                        <td>
                            @if(Auth("admin")->user()->role_id==3)

                                <form id="status-form" method="POST" action="{{ route('admin.index-update',["task"=>$store->id]) }}">
                                    @csrf
                                    <select name="status" onchange="document.getElementById('status-form').submit();">
                                        <option value="pending" {{ $store->status == 'pending' ? 'selected' : '' }}>
                                            {{ translate('messages.pending') }}
                                        </option>
                                        <option value="completed" {{ $store->status == 'completed' ? 'selected' : '' }}>
                                            {{ translate('messages.completed') }}
                                        </option>
                                        <option value="needHelp" {{ $store->status == 'needHelp' ? 'selected' : '' }}>
                                            {{ translate('messages.needHelp') }}
                                        </option>
                                        <option value="fail" {{ $store->status == 'fail' ? 'selected' : '' }}>
                                            {{ translate('messages.fail') }}
                                        </option>
                                    </select>
                                </form>

@else
                                <h5>
                                    {{$store->status}}
                                </h5>
                            @endif
                        </td>
                        <td>
                            <h5>{{translate("messages.".$store->priority)??translate("messages.not_found")}}</h5>
                        </td>
                        <td>
                            <h5>{{$store->due_date}}</h5>
                        </td>
                        <td>
                            <h5>{{$store->assignedBY->f_name}} {{$store->assignedBY->l_name}}</h5>
                        </td>
                        <td>
                            <h5>{{$store->assignedTo->f_name}} {{$store->assignedTo->l_name}}</h5>
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
        $("#store").hide();
        $("#storesManges").hide();

        $('select[name="type"]').on('change',function(){
           if(this.value=="add_store"){
               $("#store").show();
               $("#storesManges").hide();
           }else if(this.value=="update_store"){
               $("#store").hide();
               $("#storesManges").show();

           }else{
               $("#store").hide();
               $("#storesManges").hide();

           }
        });

    </script>
@endpush
