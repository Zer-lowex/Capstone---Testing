
@extends('admin.layout')

@section('title', 'Activity Logs')

@section('content')



    <h1 class="title">Activity Log</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Dashboard</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head d-flex justify-content-between align-items-center">
                <h4>Activity Logs</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button id="btnPrint" class="btn btn-primary">
                        <i class="bx bxs-printer"></i> Print
                    </button>
                </div>
            </div>

            <div class="collapse" id="filterOptions">
                <div class="info-data">
                    <form action="{{ url('/admin/activityLog') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="usertype" class="form-label">Filter by User Type:</label>
                                <select name="usertype" id="usertype" class="form-select">
                                    <option value="">All</option>
                                    <option value="Admin" {{ request('usertype') == 'Admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="Staff" {{ request('usertype') == 'Staff' ? 'selected' : '' }}>Staff
                                    </option>
                                    <option value="Cashier" {{ request('usertype') == 'Cashier' ? 'selected' : '' }}>Cashier
                                    </option>
                                    <option value="Owner" {{ request('usertype') == 'Owner' ? 'selected' : '' }}>Owner
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
                                <a href="{{ url('/admin/activityLog') }}" class="btn btn-primary ms-2">Clear Filters</a>
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
                                <th class="id-column"> </th>
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
                                        <td class="id-column">
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteActivityLogModal"
                                                data-id="{{ $activityLog->id }}">
                                                <i class="bx bxs-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $activity_logs->links() }}
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteActivityLogModal" tabindex="-1" aria-labelledby="deleteActivityLogModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header text-black justify-content-center">
                            <h5 class="modal-title fw-bold" id="deleteActivityLogModalLabel">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            Are you sure you want to delete this Activity Log?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form id="deleteActivityLogForm" method="POST" action="">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteActivityLogModal = document.getElementById('deleteActivityLogModal');
            const deleteActivityLogForm = document.getElementById('deleteActivityLogForm');

            deleteActivityLogModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                const activityLogId = button.getAttribute('data-id');

                // Update the form action dynamically
                deleteActivityLogForm.action = `/admin/activityLog/delete/${activityLogId}`;
            });
        });
    </script>

    {{-- script for Printing Activity Logs Table --}}
    <script>
        document.getElementById('btnPrint').addEventListener('click', function () {
            document.querySelectorAll('.id-column').forEach(el => el.style.display = 'none');

            const reportContent = document.querySelector('.card').cloneNode(true);
            
            // Remove unwanted elements (forms, buttons, pagination, modals)
            reportContent.querySelectorAll('form, button, .pagination, .modal').forEach(el => el.remove());

            // Extract report details safely
            const reportDetailsElement = reportContent.querySelector('.row');
            const reportDetails = reportDetailsElement?.innerHTML || '';

            if (reportDetailsElement) {
                reportDetailsElement.remove();
            }

            // Get the date filter value if applied
            const dateFilter = document.getElementById('date').value;
            let formattedDate = '';

            if (dateFilter) {
                const dateObj = new Date(dateFilter);
                formattedDate = dateObj.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }

            const dateInfo = formattedDate ? `<p><strong>Filtered Date:</strong> ${formattedDate}</p>` : '';

            // Open new print window
            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Activity Logs Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: left; padding: 20px; }
                        h2 { font-size: 24px; font-weight: bold; text-align: center; }
                        .report-details { margin-bottom: 20px; }
                        .report-details p { margin: 5px 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; text-align: center; }
                        th, td { border: 1px solid #000; padding: 8px; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                    </style>
                </head>
                <body>
                    <h2>Activity Logs Report</h2>
                    <div class="report-details">
                        ${dateInfo}
                    </div>
                    ${reportContent.innerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            
            setTimeout(() => {
            document.querySelectorAll('.id-column').forEach(el => el.style.display = '');
        }, 100);
        });

    </script>
    
    

@endsection
