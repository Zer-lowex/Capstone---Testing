@extends('admin.layout')

@section('title', 'Edit Supplier')

@section('content')
<h1 class="title">Edit Supplier</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Edit Supplier</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h4>Edit Supplier
                            <a href="{{ url('/admin/supplier') }}" class="btn btn-primary float-end">Back</a>
                        </h4> 
                    </div>
                    <div class="card-body modern-card-body">
                        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" />
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Email (Optional)</label>
                                <input type="text" name="email" class="form-control" value="{{ $supplier->email }}" />
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}" />
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $supplier->address }}" />
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
