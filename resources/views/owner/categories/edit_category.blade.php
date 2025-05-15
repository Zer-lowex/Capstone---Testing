@extends('owner.layout')

@section('title', 'Edit Category')

@section('content')
    
<h1 class="title">Edit Category</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Edit Category</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h4>Edit Category
                            <a href="{{ url('/owner/category') }}" class="btn btn-primary float-end">Back</a>
                        </h4> 
                    </div>
                    <div class="card-body modern-card-body">
                        <form action="{{ route('owner.category.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" />
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
