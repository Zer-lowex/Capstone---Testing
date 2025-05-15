@extends('staff.layout')

@section('title', 'Activity Logs')

@section('content')


    <h1 class="title">Activity Log</h1>
    <ul class="breadcrumbs">
        <li><a href="{{ route('staff.dashboard') }}">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Dashboard</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head d-flex justify-content-between align-items-center">
                <h4>Activity Logs</h4>
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>

            <div class="collapse" id="filterOptions">
                <div class="info-data">
                    <form action="{{ url('/staff/activityLog') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="usertype" class="form-label">Filter by User Type:</label>
                                <select name="usertype" id="usertype" class="form-select">
                                    <option value="" >All
                                    </option>
                                    <option value="Staff" {{ request('usertype') == 'Staff' ? 'selected' : '' }}>Staff
                                    </option>
                                    <option value="Cashier" {{ request('usertype') == 'Cashier' ? 'selected' : '' }}>Cashier
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Filter by Date:</label>
                                <input type="date" name="date" id="date" class="form-control"
                                    value="{{ request('date') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <a href="{{ url('/staff/activityLog') }}" class="btn btn-primary ms-2">Clear Filters</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="info-data">
                <div class="container">
                    <table class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>TIME</th>
                                <th>USER</th>
                                <th>ACTION</th>
                                <th>DESCRIPTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($activity_logs->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No records found for the selected
                                        filters.</td>
                                </tr>
                            @else
                                @foreach ($activity_logs as $activityLog)
                                    <tr>
                                        <td>{{ $activityLog->id }}</td>
                                        <td>{{ $activityLog->updated_at }}</td>
                                        <td>
                                            @if ($activityLog->user)
                                                {{ $activityLog->user->usertype }}
                                            @else
                                                <span class="text-muted">System / Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $activityLog->action }}</td>
                                        <td>{{ $activityLog->description }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $activity_logs->links() }}
                </div>
            </div>
        </div>
    </div>
    @endsection
