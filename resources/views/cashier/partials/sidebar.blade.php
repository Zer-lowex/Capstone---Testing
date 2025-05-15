<a href="{{ url('cashier/dashboard') }}" class="brand">
    <img src="{{ asset('assets/images/kc.png') }}" alt="KC Enterprises Logo" class="icon" style="width: 15px; height: 20px; vertical-align: middle; margin-left: 2px;">
     Enterprises
</a>
<ul class="side-menu">
    <li><a href="{{ url('cashier/dashboard') }}" class="active"><i class='bx bxs-dashboard icon'></i><strong> POS</strong></a></li>
    <li class="divider" data-text="main">Main</li>
    
    <li><a href="{{ url('cashier/products') }}"><i class='bx bxs-box icon'></i> Products</a></li>
    <li><a href="{{ url('cashier/customers') }}"><i class='bx bxs-user icon'></i> Customers</a></li>
        
    <li class="divider" data-text="Transactions">Transactions</li>

    <li><a href="{{ url('cashier/orders') }}"><i class='bx bxs-shopping-bag icon'></i> Orders</a></li>
    <li><a href="{{ url('cashier/products/reserved') }}"><i class='bx bxs-bookmarks icon'></i> Reserved</a></li>
</ul>
<div class="ads">
	<div class="wrapper">
		<div class="small">Logged in as:</div>
        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} ({{ auth()->user()->usertype }})</strong>
	</div>
</div>
