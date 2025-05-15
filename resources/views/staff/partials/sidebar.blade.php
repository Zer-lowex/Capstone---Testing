<a href="{{ url('staff/dashboard') }}" class="brand">
    <img src="{{ asset('assets/images/kc.png') }}" alt="KC Enterprises Logo" class="icon" style="width: 15px; height: 20px; vertical-align: middle; margin-left: 2px;">
     Enterprises
</a>
<ul class="side-menu">
    <li><a href="{{ url('staff/dashboard') }}" class="active"><i class='bx bxs-dashboard icon'></i>Dashboard</a></li>
    <li class="divider" data-text="main">Main</li>
    
    <li><a href="{{ url('staff/products') }}"><i class='bx bxs-box icon'></i> Products</a></li>
    <li><a href="{{ url('staff/supplier') }}"><i class='bx bxs-store icon'></i> Suppliers</a></li>
        
    <li class="divider" data-text="Transactions">Transactions</li>

    <li><a href="{{ url('staff/orders') }}"><i class='bx bxs-shopping-bag icon'></i> Orders</a></li>

    <li>
		<a href="#"><i class='bx bxs-package icon'></i> Deliveries <i class='bx bx-chevron-right icon-right'></i></a>
		<ul class="side-dropdown">
			<li>
				<a href="{{ url('staff/deliveries') }}" class="icon-link"> 
                    <i class='bx bxs-hourglass'></i> Pending Deliveries </a>
			</li>
			<li>
				<a href="{{ url('staff/complete-deliveries') }}" class="icon-link"> 
                    <i class='bx bx-check-circle'></i> Completed Deliveries </a>
			</li>
		</ul>
	</li>

    <li class="divider" data-text="Records">Records</li>

    <li>
		<a href="#"><i class='bx bxs-file icon'></i> Reports <i class='bx bx-chevron-right icon-right'></i></a>
		<ul class="side-dropdown">
			<li>
				<a href="{{ url('staff/reports') }}" class="icon-link"> 
                    <i class='bx bxs-bar-chart-square'></i> Sales Reports </a>
			</li>
			<li>
				<a href="{{ url('staff/inventory-reports') }}" class="icon-link"> 
                    <i class='bx bxs-doughnut-chart'></i> Inventory Report </a>
			</li>
		</ul>
	</li>
    <li><a href="{{ url('staff/activityLog') }}"><i class='bx bxs-box icon'></i> Activity Log</a></li>
</ul>
<div class="ads">
	<div class="wrapper">
		<div class="small">Logged in as:</div>
        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} ({{ auth()->user()->usertype }})</strong>
	</div>
</div>
