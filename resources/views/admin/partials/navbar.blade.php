<nav>
    <i class='bx bx-menu toggle-sidebar'></i>
    
    <div class="nav-right">
        <span class="divider"></span>
        <div class="profile">
            <i class="bx bxs-user-circle icon"></i>
            <ul class="profile-link">
                <li><a href="{{ route('admin.profile') }}"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    this.closest('form').submit();">
                            <i class='bx bxs-log-out-circle'></i>
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>