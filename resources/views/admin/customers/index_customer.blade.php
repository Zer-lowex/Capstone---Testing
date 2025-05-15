@extends('admin.layout')

@section('title', 'Customers')

@section('content')

<h1 class="title">Customer</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Customer</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Customer List</h4>
                        <div class="d-flex position-relative">
                            <input type="text" id="customerSearch" class="form-control me-2 search-bar"
                                   placeholder="Search Customer..." autocomplete="off">
                            <span id="clearSearch" style="display: none; cursor: pointer; position: absolute; right: 125px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-times"></i>
                            </span>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addCustomerModal">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="data">
                        <table id="customerTable" class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th>USERNAME</th>
                                    <th>FIRST NAME</th>
                                    <th>LAST NAME</th>
                                    <th>PHONE</th>
                                    <th>ADDRESS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer) 
                                <tr>
                                    <td>{{ $customer->username }}</td>
                                    <td>{{ $customer->first_name }}</td>
                                    <td>{{ $customer->last_name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>
                                        <a href="{{ url('/admin/customers/edit', $customer->id) }}" class="btn btn-success"><i class="bx bxs-edit"></i></a>
                                        <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCustomerModal"
                                            data-id="{{ $customer->id }}">
                                            <i class="bx bxs-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $customers->links() }}
                    </div>                  
                </div>

                <!-- Popup form for New Customer -->
                <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="addCustomerModalLabel">Add Customer</h5>
                                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form action="{{ url('/admin/customers/add') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control rounded-3" placeholder="Enter First Name" />
                                        @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control rounded-3" placeholder="Enter Last Name" />
                                        @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Username</label>
                                        <input type="text" name="username" value="{{ old('username') }}" class="form-control rounded-3" placeholder="Enter Username" />
                                        @error('username') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control rounded-3" placeholder="Enter Customer Phone" />
                                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address</label>
                                        <input type="text" name="address" value="{{ old('address') }}" class="form-control rounded-3" placeholder="Enter Customer Address" />
                                        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success text-white">Create Customer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-black justify-content-center">
                                <h5 class="modal-title fw-bold" id="deleteCustomerModalLabel">Delete Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                Are you sure you want to delete this customer?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form id="deleteCustomerForm" method="POST" action="">
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

    @if (session('showAddCustomerModal') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
            addCustomerModal.show();
        });
    </script>
    @endif

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#customerSearch').on('keyup', function() {
        let searchTerm = $(this).val();

        $.ajax({
            url: '{{ route('admin.customer.search') }}',
            type: 'GET',
            data: { term: searchTerm },
            success: function(data) {
                let tableData = '';

                if (data.length > 0) {
                    data.forEach(function(customer) {
                        tableData += `
                            <tr>
                                <td>${customer.username}</td>
                                <td>${customer.first_name}</td>
                                <td>${customer.last_name}</td>
                                <td>${customer.phone}</td>
                                <td>${customer.address}</td>
                                <td>
                                    <a href="{{ url('admin/customers/edit/') }}/${customer.id}" class="btn btn-success"><i class="bx bxs-edit"></i></a>
                                    <form action="{{ url('admin/customers/delete/') }}/${customer.id}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Customer?')">
                                            <i class="bx bxs-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableData = `<tr><td colspan="6">No results found</td></tr>`;
                }

                $('table#customerTable tbody').html(tableData);
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
        const deleteCustomerModal = document.getElementById('deleteCustomerModal');
        const deleteCustomerForm = document.getElementById('deleteCustomerForm');

        deleteCustomerModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            const customerId = button.getAttribute('data-id');

            // Update the form action dynamically
            deleteCustomerForm.action = `/admin/customers/delete/${customerId}`;
        });
    });
</script>
@endsection

