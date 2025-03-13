<nav class="col-md-3 col-lg-2 d-md-block sidebar">
    <input type="text" class="form-control mb-3" id="search" placeholder="Search menu...">
    <ul class="menu list-unstyled" id="menuList">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @if (auth()->user() && auth()->user()->role == 'admin')
        <li><a href="{{ route('users.index') }}">Users</a></li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="{{ route('message') }}" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Messages
            </a>
            <ul class="dropdown-menu" aria-labelledby="messagesDropdown">
                <li><a class="dropdown-ite text-white" href="{{ route('message') }}">Inbox</a></li>
                @if (auth()->user() && auth()->user()->role == 'admin')
                <li><a class="dropdown-item text-white" href="{{ route('all.message') }}">All Archived</a></li>
            @endif
                
            </ul>
        </li>
        <li><a href="#">Notifications</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Settings</a></li>
    </ul>
</nav>

<style>
    .dropdown-item:hover {
        color: var(--bs-dropdown-link-hover-color);
        background-color: #1a252f !important;
    }

    

    .dropdown-menu {
        background: #1a252f;
    }
</style>