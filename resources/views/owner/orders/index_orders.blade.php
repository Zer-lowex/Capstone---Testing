@extends('owner.layout')

@section('title', 'Orders')

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
                        <h4>Recent Orders</h4>
                        <form method="GET" action="{{ route('owner.order.view') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="filter" class="form-control" style="width: 115%; margin-right: 15px;">
                                        <option value="">SHOW ALL</option>
                                        <option value="ONGOING" {{ request('filter') == 'ONGOING' ? 'selected' : '' }}>ONGOING</option>
                                        <option value="PENDING" {{ request('filter') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                        <option value="COMPLETE" {{ request('filter') == 'COMPLETE' ? 'selected' : '' }}>COMPLETE</option>
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
                                    <th>CASHIER ID</th>
                                    <th>CUSTOMER</th>
                                    <th>AMOUNT PAID</th>
                                    <th>DISCOUNT</th>
                                    <th>DELIVERY</th>
                                    <th>DELIVERY FEE</th>
                                    <th>CUSTOMER ADDRESS</th>
                                    <th>STATUS</th>
                                    <th>DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($sales->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No Results Found
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($sales as $sale) 
                                    <tr>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ $sale->user->id }}</td>
                                        <td>{{ $sale->customer->username ?? 'WALK-IN'}}</td>
                                        <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                                        <td>₱{{ number_format($sale->discount, 2) }}</td>
                                        <td>{{ $sale->delivery }}</td>
                                        <td>₱{{ number_format(floatval($sale->delivery_fee), 2) }}</td>
                                        <td>{{ $sale->customer_address }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $sale->status === 'COMPLETE' ? 'bg-success' : 
                                                   ($sale->status === 'PENDING' ? 'bg-warning' : 'bg-secondary') }}
                                                px-3 py-1 fs-6">
                                                {{ $sale->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('owner.receipt.view', $sale->id) }}" class="btn btn-primary">
                                                <i class="bx bxs-receipt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>                            
                        </table>
                        {{ $sales->appends(request()->query())->links() }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@endsection

