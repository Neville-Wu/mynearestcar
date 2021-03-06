<ul class="sidebar-menu">
    <li class="{{ Request::route()->getName() == 'home' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
    </li>

    <li class="{{ Request::route()->getName() == 'rent.index' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('rent.index') }}">
            <i class="fas fa-car-side"></i> <span>Rent a Car</span>
        </a>
    </li>

    <li class="{{ Request::route()->getName() == 'contact' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('contact') }}">
            <i class="fas fa-address-card"></i> <span>Contact Us</span>
        </a>
    </li>
</ul>
