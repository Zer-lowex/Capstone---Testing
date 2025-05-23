<a href="{{ url('admin/dashboard') }}" class="brand">
    <img src="{{ asset('assets/images/kc.png') }}" alt="KC Enterprises Logo" class="icon" style="width: 15px; height: 20px; vertical-align: middle; margin-left: 2px;">
     Enterprises
</a>
<ul class="side-menu">
    <li><a href="{{ url('admin/dashboard') }}" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
    <li class="divider" data-text="Account">Account</li>
    <li><a href="{{ url('admin/settings/profile') }}"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
    <li class="divider" data-text="System">System</li>
    <li><a href="{{ url('admin/settings/alerts') }}"><i class='bx bxs-bell-ring icon'></i> Alerts</a></li>
    <li><a href="{{ url('admin/settings/site') }}"><i class='bx bxs-server icon'></i> Product List</a></li>

    
</ul>
<div class="ads">
	<div class="wrapper">
		<div class="small">Logged in as:</div>
        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} ({{ auth()->user()->usertype }})</strong>
	</div>
</div>
