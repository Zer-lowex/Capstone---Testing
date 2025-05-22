@extends('cashier.layout')

@section('title', 'Reserved Products')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">Reserved Products</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reserved Products</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if ($customers->isEmpty())
                            <div class="alert alert-info mb-0 text-center">No reserved products found.</div>
                        @else
                            @foreach ($customers as $customer)
                                @php
                                    $customerReservations = $allReservations->get($customer->id, collect());
                                @endphp

                                <div class="customer-section mb-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">
                                            <i class="fas fa-user me-2"></i>{{ $customer->username }}
                                        </h4>
                                        <span class="badge bg-primary">
                                            {{ $customerReservations->count() }}
                                            {{ Str::plural('item', $customerReservations->count()) }}
                                        </span>
                                        <form action="{{ route('cashier.reservations.accept') }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                title="Accept Reservation">
                                                <i class="bx bx-check-circle"></i> Accept
                                            </button>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center align-middle" width="10%">Product ID</th>
                                                    <th class="text-center align-middle" width="20%">Name</th>
                                                    <th class="text-center align-middle" width="15%">Unit</th>
                                                    <th class="text-center align-middle" width="15%">Category</th>
                                                    <th class="text-center align-middle" width="10%">Qty</th>
                                                    <th class="text-center align-middle" width="10%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($customerReservations as $reservation)
                                                    <tr class="text-center align-middle">
                                                        <td>{{ $reservation->product->id ?? 'N/A' }}</td>
                                                        <td>{{ $reservation->product->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($reservation->product && $reservation->product->unit)
                                                                {{ is_array($reservation->product->unit) ? $reservation->product->unit['name'] : $reservation->product->unit->name }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ $reservation->product->category->name ?? 'N/A' }}</td>
                                                        <td>{{ $reservation->quantity ?? 0 }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger cancel-btn ms-2"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                data-reservation-id="{{ $reservation->id }}"
                                                                title="Cancel Reservation">
                                                                <i class="bx bx-x-circle"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-center mt-4">
                                {{ $customers->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this reservation? The reserved quantity will be returned to stock.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmCancel" class="btn btn-danger">Confirm Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DELETE CONFIRMATION --}}
    <script>
        $(document).ready(function() {
            // Enable Bootstrap tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            let currentReservationId = null;

            // Set up modal trigger
            $(document).on('click', '.cancel-btn', function(e) {
                e.preventDefault();
                currentReservationId = $(this).data('reservation-id');
            });

            // Handle confirm button click
            $('#confirmCancel').click(function() {
                const $button = $(this);
                if (!currentReservationId) return;

                $button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );

                $.ajax({
                    url: "{{ route('cashier.reservations.cancel') }}",
                    method: "POST",
                    data: {
                        reservation_id: currentReservationId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteModal').modal('hide');
                            showToast('success', response.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('error', response.message ||
                                'Failed to cancel reservation');
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'An error occurred';
                        showToast('error', 'Error: ' + errorMsg);
                    },
                    complete: function() {
                        $button.prop('disabled', false).html('Confirm Cancel');
                    }
                });
            });

            function showToast(type, message) {
                const toast = `<div class="toast align-items-center text-white bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
                $('#toastContainer').append(toast);
                setTimeout(() => $('.toast').toast('hide').on('hidden.bs.toast', function() {
                    $(this).remove()
                }), 3000);
            }
        });
    </script>
@endsection
