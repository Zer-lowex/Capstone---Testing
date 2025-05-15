<a href="{{ url('owner/dashboard') }}" class="brand">
    <img src="{{ asset('assets/images/kc.png') }}" alt="KC Enterprises Logo" class="icon" style="width: 15px; height: 20px; vertical-align: middle; margin-left: 2px;">
     Enterprises
</a>
<ul class="side-menu">
    <li><a href="{{ url('owner/dashboard') }}" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
    <li class="divider" data-text="main">Main</li>
    <li>
        <a href="#"><i class='bx bxs-inbox icon'></i> Products <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li>
                <a href="{{ url('owner/products') }}" class="icon-link">
                    <i class='bx bxs-box'></i> Products
                </a>
            </li>
            <li>
                <a href="{{ url('owner/category') }}" class="icon-link">
                    <i class='bx bxs-category'></i> Categories
                </a>
            </li>
            <li>
                <a href="{{ url('owner/unit') }}" class="icon-link">
                    <i class='bx bxs-ruler'></i> Units
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#"><i class='bx bxs-user icon'></i> People <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li>
                <a href="{{ url('owner/customers') }}" class="icon-link">
                    <i class='bx bxs-user-circle'></i> Customer
                </a>
            </li>
            <li>
                <a href="{{ url('owner/staff') }}" class="icon-link">
                    <i class='bx bxs-group'></i> Staff
                </a>
            </li>
            <li>
                <a href="{{ url('owner/supplier') }}" class="icon-link">
                    <i class='bx bxs-truck'></i> Supplier
                </a>
            </li>
        </ul>
    </li>
    <li class="divider" data-text="Transactions">Transactions</li>
    <li><a href="{{ url('owner/orders') }}"><i class='bx bxs-shopping-bag icon'></i> Orders</a></li>

    <li>
        <a href="#"><i class='bx bxs-package icon'></i> Deliveries <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li>
                <a href="{{ url('owner/pending-deliveries') }}" class="icon-link">
                    <i class='bx bxs-hourglass-bottom'></i> Pending Deliveries
                </a>
            </li>
            <li>
                <a href="{{ url('owner/complete-deliveries') }}" class="icon-link">
                    <i class='bx bxs-check-circle'></i> Complete Deliveries
                </a>
            </li>
        </ul>
    </li>

    <li class="divider" data-text="Records">Records</li>
    <li>
		<a href="#"><i class='bx bxs-file icon'></i> Reports <i class='bx bx-chevron-right icon-right'></i></a>
		<ul class="side-dropdown">
			<li>
				<a href="{{ url('/owner/reports') }}" class="icon-link"> 
                    <i class='bx bxs-bar-chart-square'></i> Sales Reports </a>
			</li>
			<li>
				<a href="{{ url('/owner/inventory-reports') }}" class="icon-link"> 
                    <i class='bx bxs-doughnut-chart'></i> Inventory Report </a>
			</li>
            <li>
				<a href="{{ url('/owner/cashier-reports') }}" class="icon-link"> 
                    <i class='bx bxs-receipt'></i> Cashier Report </a>
			</li>
		</ul>
	</li>
	<li><a href="{{ url('owner/activityLog') }}"><i class='bx bxs-box icon'></i> Activity Log</a></li>
</ul>
<div class="ads">
	<div class="wrapper">
		<div class="small">Logged in as:</div>
        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} ({{ auth()->user()->usertype }})</strong>
	</div>
</div>
