@extends('owner.layout')

@section('title', 'Category')

@section('content')

    <h1 class="title">Category</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">View Category</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Category List</h4>
                        <div class="d-flex position-relative">
                            <input type="text" id="categorySearch" class="form-control me-2 search-bar"
                                placeholder="Search Category..." autocomplete="off">
                            <span id="clearSearch"
                                style="display: none; cursor: pointer; position: absolute; right: 125px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-times"></i>
                            </span>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addCategoryModal">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="data">
                        <table class="table table-striped table-bordered text-center align-middle" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>PREFIX</th>
                                    {{-- <th>ACTIONS</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->prefix }}</td>
                                        {{-- <td>
                                            <a href="{{ route('owner.category.edit', $category->id) }}" class="btn btn-success"><i
                                                    class="bx bxs-edit"></i></a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteCategoryModal"
                                                data-id="{{ $category->id }}">
                                                <i class="bx bxs-trash-alt"></i>
                                            </button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $categories->links() }}
                        </div>
                    </div>

                    <!-- Popup form for New Category -->
                    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                    <h5 class="modal-title fw-bold" id="addCategoryModalLabel">Add Category</h5>
                                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('owner.category.add') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Name</label>
                                            <input type="text" name="name" class="form-control rounded-3"
                                                placeholder="Enter Category Name" />
                                                
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="button" class="btn btn-light border"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success text-white">Create
                                                Category</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                    <h5 class="modal-title fw-bold" id="deleteCategoryModalLabel">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    Are you sure you want to delete this Category?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form id="deleteCategoryForm" method="POST" action="">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>




<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#categorySearch').on('keyup', function() {
        let searchTerm = $(this).val();

        $.ajax({
            url: '{{ route('owner.category.search') }}',
            type: 'GET',
            data: { term: searchTerm },
            success: function(data) {
                let tableData = '';

                if (data.length > 0) {
                    data.forEach(function(category) {
                        tableData += `
                            <tr>
                                <td>${category.id}</td>
                                <td>${category.name}</td>
                                <td>${category.prefix}</td>
                            </tr>
                        `;
                    });
                } else {
                    tableData = `<tr><td colspan="3">No results found</td></tr>`;
                }

                $('table#categoryTable tbody').html(tableData);
                $('#paginationLinks').hide();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteCategoryModal = document.getElementById('deleteCategoryModal');
        const deleteCategoryForm = document.getElementById('deleteCategoryForm');

        deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            const categoryId = button.getAttribute('data-id');

            // Update the form action dynamically
            deleteCategoryForm.action = `/owner/category/delete/${categoryId}`;
        });
    });
</script>

@endsection
