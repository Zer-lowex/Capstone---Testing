@extends('admin.layout')

@section('title', 'Deliveries')

@section('content')

    <h1 class="title">Deliveries</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Orders</a></li>
    </ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Manage Deliveries</h4>
                        <form method="GET" action="{{ route('admin.delivery.view') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="filter" class="form-control" style="width: 115%; margin-right: 15px;">
                                        <option value="">SHOW ALL</option>
                                        <option value="ONGOING" {{ request('filter') == 'ONGOING' ? 'selected' : '' }}>
                                            ONGOING</option>
                                        <option value="PENDING" {{ request('filter') == 'PENDING' ? 'selected' : '' }}>
                                            PENDING</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="data">
                        <table id="orderTable" class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>SALE ID</th>
                                    <th>DRIVER</th>
                                    <th>CUSTOMER ADDRESS</th>
                                    <th>STATUS</th>
                                    <th>DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($deliveries->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No Deliveries Found.</td>
                                    </tr>
                                @else
                                    @foreach ($deliveries as $delivery)
                                        <tr>
                                            <td>{{ $delivery->id }}</td>
                                            <td>{{ $delivery->sale_id }}</td>
                                            <td>{{ $delivery->user ? $delivery->user->username : 'No Driver Assigned' }}
                                            </td>
                                            <td>{{ $delivery->sale->customer_address }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    {{ $delivery->status === 'COMPLETE' ? 'bg-success' : ($delivery->status === 'PENDING' ? 'bg-warning' : 'bg-secondary') }}
                                                    px-3 py-1 fs-6">
                                                            {{ $delivery->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.receipt.view', $delivery->sale->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="bx bxs-receipt"></i>
                                                </a>
                                                <button class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#assignDriverModal"
                                                    data-delivery-id="{{ $delivery->id }}">
                                                    <i class="bx bx-user"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $deliveries->appends(request()->query())->links() }}
                    </div>
                </div>

                <!-- Modal for Assign Driver -->
                <div class="modal fade" id="assignDriverModal" tabindex="-1" aria-labelledby="assignDriverModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-3">
                            <!-- Modal Header -->
                            <div class="modal-header text-black">
                                <h5 class="modal-title fw-bold mx-auto" id="assignDriverModalLabel">Assign Driver to
                                    Delivery</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form id="assignDriverForm">
                                    <div class="mb-3">
                                        <label for="driverSelect" class="form-label">Select Driver</label>
                                        <select name="driver" id="driverSelect" class="form-select">
                                            <!-- Option for No Driver Assigned -->
                                            <option value="">Remove Assigned Driver</option>

                                            <!-- Loop through drivers and display options -->
                                            @foreach ($drivers as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" id="deliveryId" name="delivery_id">
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                            <i class="bx bx-x-circle"></i> Cancel
                                        </button>
                                        <button type="submit" id="assignDriverBtn" class="btn btn-success px-4">
                                            <i class="bx bx-check-circle"></i> Assign Driver
                                        </button>
                                    </div>
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
            // When the Assign Driver button is clicked
            $('#assignDriverModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var deliveryId = button.data(
                    'delivery-id'); // Extract the delivery id from data-* attributes

                // Set the delivery id in the hidden field
                $('#deliveryId').val(deliveryId);
            });

            // Handle the form submission to assign or remove the driver
            $('#assignDriverForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                var driverId = $('#driverSelect').val(); // Get the selected driver id
                var deliveryId = $('#deliveryId').val(); // Get the delivery id

                // AJAX request to assign/remove the driver
                $.ajax({
                    url: '{{ route('admin.assign.driver') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        driver_id: driverId,
                        delivery_id: deliveryId
                    },
                    success: function(response) {
                        // Hide the modal
                        $('#assignDriverModal').modal('hide');

                        // Save the success message to sessionStorage
                        sessionStorage.setItem('toastrMessage', response.message);

                        // Optionally reload the page
                        location.reload();
                    },
                    error: function(xhr) {
                        // Show an error message with Toastr
                        toastr.error('Failed to assign/remove driver. Please try again.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            // Check if there is a toastr message stored in sessionStorage
            var toastrMessage = sessionStorage.getItem('toastrMessage');

            if (toastrMessage) {
                // Show the success message with Toastr
                toastr.success(toastrMessage);

                // Remove the message from sessionStorage after showing it
                sessionStorage.removeItem('toastrMessage');
            }
        });
    </script>


@endsection
