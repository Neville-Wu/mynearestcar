<ul class="sidebar-menu">
    <li class="{{ Request::route()->getName() == 'admin.index' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.index') }}">
            <i class="fas fa-user-shield"></i> <span>Admin Dashboard</span>
        </a>
    </li>

    <li class="menu-header"><i class="fas fa-car p-1 mr-2"></i> {{ __('Vehicle Management') }}</li>
    <li class="{{ Request::route()->getName() == 'admin.vehicle.index' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.vehicle.index') }}">
            <i class="fas fa-list"></i> <span>{{ __('Vehicle List') }}</span>
        </a>
    </li>
    <li class="{{ Request::route()->getName() == 'admin.vehicle.create' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.vehicle.create') }}">
            <i class="fas fa-plus"></i> <span>{{ __('Add Vehicle') }}</span>
        </a>
    </li>


    <li class="menu-header"><i class="fas fa-parking p-1 mr-2"></i> {{ __('Carpark Management') }}</li>
    <li class="{{ Request::route()->getName() == 'admin.carpark.index' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.carpark.index') }}">
            <i class="fas fa-list"></i> <span>{{ __('Carpark List') }}</span>
        </a>
    </li>
    <li class="{{ Request::route()->getName() == 'admin.carpark.create' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.carpark.create') }}">
            <i class="fas fa-plus"></i> <span>{{ __('Add Carpark') }}</span>
        </a>
    </li>

    <li class="menu-header"><i class="fas fa-receipt p-1 mr-2"></i> {{ __('Order Management') }}</li>
    <li class="{{ Request::route()->getName() == 'admin.order.search' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.order.search') }}">
            <i class="fas fa-search"></i> <span>{{ __('Search Order') }}</span>
        </a>
    </li>


    <li class="menu-header"><i class="fas fa-users p-1 mr-2"></i> {{ __('User Management') }}</li>
    <li class="{{ Request::route()->getName() == 'admin.user.search' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.user.search') }}">
            <i class="fas fa-search"></i> <span>{{ __('Search User') }}</span>
        </a>
    </li>

</ul>
