@extends('staff.layout')

@section('title', 'Deliveries')

@section('content')

<h1 class="title">Recent Orders</h1>
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
                        <h4>Deliveries</h4>
                        <form method="GET" action="{{ route('staff.delivery.view') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="filter" class="form-control" style="width: 115%; margin-right: 15px;">
                                        <option value="">SHOW ALL</option>
                                        <option value="ONGOING" {{ request('filter') == 'ONGOING' ? 'selected' : '' }}>ONGOING</option>
                                        <option value="PENDING" {{ request('filter') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
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
                                        <td>{{ $delivery->user ? $delivery->user->username : 'No Driver Assigned' }}</td>
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
                                            <a href="{{ route('staff.receipt.view', $delivery->sale->id) }}" class="btn btn-primary">
                                                <i class="bx bxs-receipt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $deliveries->appends(request()->query())->links() }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection

