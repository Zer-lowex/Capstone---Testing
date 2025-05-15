@extends('owner.layout')

@section('title', 'Staff')

@section('content')

<h1 class="title">Staff</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Staff</a></li>
</ul>

<div class="info-data">
    <div class="container">
        <div class="col-md-12">

            <div class="container-data">
                <div class="head d-flex align-items-center justify-content-between">
                    <h4>Staff List</h4>
                    <div class="d-flex position-relative">
                        <input type="text" id="staffSearch" class="form-control me-2 search-bar"
                               placeholder="Search Staff..." autocomplete="off">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addStaffModal">
                            <i class="bx bx-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="data">
                    <table id="staffTable" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>FIRST NAME</th>
                                <th>LAST NAME</th>
                                <th>USERNAME</th>
                                <th>TYPE</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $staff)
                                @if ($staff->usertype !== 'Admin' && $staff->usertype !== 'Owner')
                                    <tr>
                                        <td>{{ $staff->first_name }}</td>
                                        <td>{{ $staff->last_name }}</td>
                                        <td>{{ $staff->username }}</td>
                                        <td>{{ $staff->usertype }}</td>
                                        <td>
                                            @if ($staff->status === 'Online')
                                                <span style="color: #006400;">
                                                    <i class="bx bxs-circle me-1"></i> Online
                                                </span>
                                            @else
                                                <span class="text-secondary">
                                                    <i class="bx bxs-circle me-1"></i> Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staffDetailModal-{{ $staff->id }}">
                                                <i class="bx bxs-user-detail"></i>
                                            </button>                                                                                   
                                        </td>
                                    </tr>
                        
                                    <!-- Modal for Viewing Staff Details -->
                                    <div class="modal fade" id="staffDetailModal-{{ $staff->id }}" tabindex="-1" aria-labelledby="staffDetailModalLabel-{{ $staff->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header text-black justify-content-center">
                                                    <h5 class="modal-title fw-bold" id="staffDetailModalLabel-{{ $staff->id }}">Staff Details</h5>
                                                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p><strong>ID:</strong> {{ $staff->id }}</p>
                                                    <p><strong>Username:</strong> {{ $staff->username }}</p>
                                                    <p><strong>Usertype:</strong> {{ $staff->usertype }}</p>
                                                    <p><strong>Name:</strong> {{ $staff->first_name }} {{ $staff->last_name }}</p>
                                                    <p><strong>Email:</strong> {{ $staff->email ?? 'N/A'}}</p>
                                                    <p><strong>Phone:</strong> {{ $staff->phone ?? 'N/A'}}</p>
                                                    <p><strong>Address:</strong> {{ $staff->address ?? 'N/A'}}</p>
                                                    <p><strong>Status:</strong> <span class="{{ $staff->status === 'Online' ? 'text-success' : '' }}">{{ $staff->status }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                @endif
                            @endforeach
                        </tbody>                                               
                    </table>
                    <div id="paginationLinks">
                        {{ $users->links() }}
                    </div>
                </div>                  
            </div>

            <!-- Popup form for New Staff -->
            <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-black justify-content-center">
                            <h5 class="modal-title fw-bold" id="addStaffModalLabel">Add Staff</h5>
                            <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('owner.staff.add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">First Name</label>
                                    <input type="text" name="first_name" class="form-control rounded-3" placeholder="Enter First Name" />
                                    @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Last Name</label>
                                    <input type="text" name="last_name" class="form-control rounded-3" placeholder="Enter Last Name" />
                                    @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Username</label>
                                    <input type="text" name="username" class="form-control rounded-3" placeholder="Enter Username" />
                                    @error('username') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Usertype</label>
                                    <select name="usertype" class="form-control rounded-3">
                                        <option value="" disabled selected>Select User Type</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Cashier">Cashier</option>
                                        <option value="Driver">Driver</option>
                                    </select>
                                    @error('usertype') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 text-end">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success text-white">Create Staff</button>
                                </div>
                            </form>  
                            <div class="mt-3">
                                <p class="text-muted small">Note: The default password for the staff account is <strong>Password123</strong>.</p>
                            </div>                              
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteStaffModal" tabindex="-1" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-black justify-content-center">
                            <h5 class="modal-title fw-bold" id="deleteStaffModalLabel">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            Are you sure you want to delete this Staff Member?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form id="deleteStaffForm" method="POST" action="">
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

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#staffSearch').on('keyup', function() {
        let searchTerm = $(this).val();

        $.ajax({
            url: '{{ route('owner.staff.search') }}',
            type: 'GET',
            data: { term: searchTerm },
            success: function(data) {
                let tableData = '';

                if (data.length > 0) {
                        data.forEach(function(staff) {
                            if (staff.usertype !== 'Admin' && staff.usertype !== 'Owner') {
                                tableData += `
                                    <tr>
                                        <td>${staff.id}</td>
                                        <td>${staff.first_name}</td>
                                        <td>${staff.last_name}</td>
                                        <td>${staff.username}</td>
                                        <td>${staff.usertype}</td>
                                        <td class="${staff.status === 'Online' ? 'text-success' : ''}">${staff.status}</td>
                                        <td>
                                            <!-- Show Details Button -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staffDetailModal-${staff.id}">
                                                <i class="bx bxs-user-detail"></i>
                                            </button>

                                            <!-- Edit Button -->
                                            

                                            <!-- Delete Button -->
                                            
                                        </td>
                                    </tr>

                                    <!-- Staff Detail Modal -->
                                    <div class="modal fade" id="staffDetailModal-${staff.id}" tabindex="-1" aria-labelledby="staffDetailModalLabel-${staff.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header text-black justify-content-center">
                                                    <h5 class="modal-title fw-bold" id="staffDetailModalLabel-${staff.id}">Staff Details</h5>
                                                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p><strong>ID:</strong> ${staff.id}</p>
                                                    <p><strong>ID:</strong> ${staff.username}</p>
                                                    <p><strong>Usertype:</strong> ${staff.usertype}</p>
                                                    <p><strong>Name:</strong> ${staff.first_name} ${staff.last_name}</p>
                                                    <p><strong>Email:</strong> ${staff.email ?? 'N/A'}</p>
                                                    <p><strong>Phone:</strong> ${staff.phone ?? 'N/A'}</p>
                                                    <p><strong>Address:</strong> ${staff.address ?? 'N/A'}</p>
                                                    <p><strong>Status:</strong> <span class="${staff.status === 'Online' ? 'text-success' : ''}">${staff.status}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                    } else {
                        tableData = `<tr><td colspan="6">No results found</td></tr>`;
                    }

                $('table#staffTable tbody').html(tableData);
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
        const deleteStaffModal = document.getElementById('deleteStaffModal');
        const deleteStaffForm = document.getElementById('deleteStaffForm');

        deleteStaffModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            const staffId = button.getAttribute('data-id');

            // Update the form action dynamically
            deleteStaffForm.action = `/owner/staff/delete/${staffId}`;
        });
    });
</script>

@endsection
