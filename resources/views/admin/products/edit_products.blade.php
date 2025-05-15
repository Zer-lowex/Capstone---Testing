@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')

<h1 class="title">Edit Product</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Edit Product</a></li>
</ul>

<div class="info-data">
    <div class="container">
        <div class="col-md-12">
            <div class="card modern-card mb-4">
                <div class="card-header">
                    <h4>Edit Product
                        <a href="{{ url('/admin/products') }}" class="btn btn-primary float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body modern-card-body">
                    <form action="{{ route('product.update', $products->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $products->name }}" />
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="" disabled>Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ $category->id == $products->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Cost Price</label>
                            <input type="number" name="cost_price" class="form-control" value="{{ $products->cost_price }}" step="0.01" />
                            @error('cost_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Sell Price</label>
                            <input type="number" name="sell_price" class="form-control" value="{{ $products->sell_price }}" step="0.01" />
                            @error('sell_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="{{ $products->quantity }}" />
                            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Expiration Date</label>
                            <input type="date" name="expiration_date" class="form-control" value="{{ $products->expiration_date }}" />
                            @error('expiration_date') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label>Unit</label>
                            <select name="unit_id" class="form-control">
                                <option value="" disabled>Select a Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" 
                                        {{ $unit->id == $products->unit_id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="" disabled>Select a Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                        {{ $supplier->id == $products->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
