@extends('layouts.admin.app')

@section('title',translate('messages.request'))
@section('content')
    <div class="card p-3 m-3">
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
                    <th class="border-0">{{translate('id')}}</th>
                    <th class="border-0">{{translate('messages.name')}}</th>
                    <th class="border-0">{{translate('messages.phone')}}</th>
                    <th class="border-0">{{translate('messages.email')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.message')}}</th>
                    <th class="text-uppercase border-0">{{translate('messages.created_at')}}</th>
                </tr>
                </thead>

                <tbody id="set-rows">
                @foreach($data as $key=>$store)
                    <tr>
                        <td>{{$store->id}}</td>
                        <td>
                            {{$store->name}}
                        </td>

                        <td>
                            <div>
                                <a href="tel:{{ $store->phone }}">
                                    {{$store->phone}}
                                </a>

                            </div>
                        </td>
                        <td>
                            {{$store->email}}
                        </td>
                        <td>
                            {{$store->message}}
                        </td>

                        <td>
                            {{$store->created_at}}
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>

@endsection