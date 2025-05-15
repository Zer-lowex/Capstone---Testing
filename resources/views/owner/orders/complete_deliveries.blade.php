@extends('owner.layout')

@section('title', 'Deliveries')

@section('content')

<h1 class="title">Orders</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">View Orders</a></li>
</ul>

    <div class="info-data">
        <div class="container">
            <div class="col-md-12">

                <div class="container-data">
                    <div class="head d-flex align-items-center justify-content-between">
                        <h4>Completed Deliveries</h4>
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
                                        <td>{{ $delivery->user ? $delivery->user->username : 'No Driver Assigned' }}</td>
                                        <td>{{ $delivery->sale->customer_address }}</td>
                                        <td>{{ $delivery->status }}</td>
                                        <td>
                                            <a href="{{ route('owner.receipt.view', $delivery->sale->id) }}" class="btn btn-primary">
                                                <i class="bx bxs-receipt"></i>
                                            </a>
                                            <button class="btn btn-success view-photo"
                                                    data-delivery-id="{{ $delivery->id }}"
                                                    data-photo-path="{{ $delivery->verification }}"
                                                    title="View Verification Photo">
                                                <i class="bx bxs-camera"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $deliveries->links() }}
                    </div>      
                    
                    <div class="modal fade" id="photoModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delivery Verification #<span id="deliveryIdSpan"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div id="photoLoading" class="py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading verification photo...</p>
                                    </div>
                                    <img id="modalPhoto" src="" class="img-fluid rounded d-none" style="max-height: 70vh;">
                                    <div id="photoError" class="alert alert-danger d-none"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    document.querySelectorAll('.view-photo').forEach(btn => {
        btn.addEventListener('click', function() {
            const deliveryId = this.dataset.deliveryId;
            const photoPath = this.dataset.photoPath;
            const modal = new bootstrap.Modal('#photoModal');
            const img = document.getElementById('modalPhoto');

            document.getElementById('deliveryIdSpan').textContent = deliveryId;

            img.classList.add('d-none');
            document.getElementById('photoLoading').classList.remove('d-none');

            img.src = `/owner/verification/${deliveryId}?t=${new Date().getTime()}`;
            img.alt = `Delivery verification #${deliveryId}`;
            
            img.onload = function() {
                document.getElementById('photoLoading').classList.add('d-none');
                img.classList.remove('d-none');
            };
            
            // Show modal
            modal.show();
        });
    });
</script>
@endsection

