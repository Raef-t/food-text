@extends('layouts.admin.app')
@section('title',translate('messages.package_setup'))

@section('content')
    <div class="container">
        <h2>Update Package</h2>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('package.update', ['id' => $package->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Package Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $package->name) }}"
                       required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          required>{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"
                       value="{{ old('price', $package->price) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Package</button>
        </form>
    </div>
@endsection
