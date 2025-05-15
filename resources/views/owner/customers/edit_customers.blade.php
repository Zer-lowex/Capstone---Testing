@extends('owner.layout')

@section('title', 'Edit Customer')

@section('content')

<h1 class="title">Edit Customer</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Edit Customer</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h4>Edit Customer
                            <a href="{{ url('/owner/customers') }}" class="btn btn-primary float-end">Back</a>
                        </h4> 
                    </div>
                    <div class="card-body modern-card-body">
                        <form action="{{ route('owner.customer.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" />
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" />
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $customer->address }}" />
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Customer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
