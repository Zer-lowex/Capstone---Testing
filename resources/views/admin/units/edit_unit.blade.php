@extends('admin.layout')

@section('title', 'Edit Unit')

@section('content')
    
<h1 class="title">Edit Unit</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Edit Unit</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h4>Edit Unit
                            <a href="{{ url('/admin/unit') }}" class="btn btn-primary float-end">Back</a>
                        </h4> 
                    </div>
                    <div class="card-body modern-card-body">
                        <form action="{{ route('unit.update', $unit->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $unit->name }}" />
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Unit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
