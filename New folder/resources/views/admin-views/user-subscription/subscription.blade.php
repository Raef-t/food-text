@extends('layouts.admin.app')

@section('title',translate('messages.Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

@endpush
@section('content')
    @if($Subscriptions->status != "completed")
        <div class="d-flex justify-content-around mt-3 p-0">
            <a href="{{route('admin.parcel.subscription.updateStatus',['Subscriptions'=>$Subscriptions->id,'status'=>"completed"])}}"
               class="btn btn-success btn-same-size">Complete Order</a>

        </div>
    @endif
    @if($Subscriptions->status != "failed")

        <div class="d-flex justify-content-center mt-3">
        <a href="{{ route('admin.parcel.subscription.updateStatus', ['Subscriptions' => $Subscriptions, 'status' => 'failed']) }}"
           class="btn btn-danger btn-same-size">Cancel Order</a>
            @endif

        </div>
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="mb-0">User and Package Information</h1>
            </div>
            <div class="card-body">
                <div class="user-details mb-4"><h2 class="text-black">User Details</h2>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">Name</th>
                            <td>
                                <a href="{{ route('admin.customer.view', [$Subscriptions->user->id]) }}">{{ $Subscriptions->user->f_name }} {{ $Subscriptions->user->l_name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>{{ $Subscriptions->user->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">phone</th>
                            <td>{{ $Subscriptions->user->phone }}</td>
                        </tr>

                        <!-- Add more user information as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="package-details"><h2 class="text-black">Package Details</h2><table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">Package Name</th>
                            <td>{{ $Subscriptions->package->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Package km</th>
                            <td>{{ $Subscriptions->package->km }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Package Price</th>
                            <td>${{ $Subscriptions->price }}</td>
                        </tr>
                        <!-- Add more package details as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection