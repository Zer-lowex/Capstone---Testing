@extends('admin.layout')

@section('title', 'Unit')

@section('content')

    <h1 class="title">Unit</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">View Unit</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Unit List</h4>
                        <div class="d-flex position-relative">
                            <input type="text" id="unitSearch" class="form-control me-2 search-bar"
                                placeholder="Search Unit..." autocomplete="off">
                            <span id="clearSearch"
                                style="display: none; cursor: pointer; position: absolute; right: 125px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-times"></i>
                            </span>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addUnitModal">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="data">
                        <table class="table table-striped table-bordered text-center align-middle" id="unitTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td>{{ $unit->id }}</td>
                                        <td>{{ $unit->name }}</td>
                                        <td>
                                            <a href="{{ route('unit.edit', $unit->id) }}" class="btn btn-success"><i
                                                    class="bx bxs-edit"></i></a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteUnitModal"
                                                data-id="{{ $unit->id }}">
                                                <i class="bx bxs-trash-alt"></i>
                                            </button>                                                
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $units->links() }}
                        </div>
                    </div>

                    <!-- Popup form for New Unit -->
                    <div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                    <h5 class="modal-title fw-bold" id="addUnitModalLabel">Add Unit</h5>
                                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('unit.add') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Name</label>
                                            <input type="text" name="name" class="form-control rounded-3"
                                                placeholder="Enter Unit Name" />
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="button" class="btn btn-light border"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success text-white">Create
                                                Unit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteUnitModal" tabindex="-1" aria-labelledby="deleteUnitModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header text-black justify-content-center">
                                    <h5 class="modal-title fw-bold" id="deleteUnitModalLabel">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    Are you sure you want to delete this Unit?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form id="deleteUnitForm" method="POST" action="">
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
    $('#unitSearch').on('keyup', function() {
        let searchTerm = $(this).val();

        $.ajax({
            url: '{{ route('unit.search') }}',
            type: 'GET',
            data: { term: searchTerm },
            success: function(data) {
                let tableData = '';

                if (data.length > 0) {
                    data.forEach(function(unit) {
                        tableData += `
                            <tr>
                                <td>${unit.id}</td>
                                <td>${unit.name}</td>
                                <td>
                                    <a href="{{ url('admin/unit/edit/') }}/${unit.id}" class="btn btn-success"><i class="bx bxs-edit"></i></a>
                                    <form action="{{ url('admin/unit/delete/') }}/${unit.id}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Unit?')">
                                            <i class="bx bxs-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableData = `<tr><td colspan="3">No results found</td></tr>`;
                }

                $('table#unitTable tbody').html(tableData);
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
        const deleteUnitModal = document.getElementById('deleteUnitModal');
        const deleteUnitForm = document.getElementById('deleteUnitForm');

        deleteUnitModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            const unitId = button.getAttribute('data-id');

            // Update the form action dynamically
            deleteUnitForm.action = `/admin/unit/delete/${unitId}`;
        });
    });

</script>

@endsection
