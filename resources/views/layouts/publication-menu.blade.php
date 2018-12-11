<!-- Menu -->
<div class="menu">
    <ul class="list">
        <li class="header">MENU</li>
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}" >
            <a href="{{ url('/dashboard') }}">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
        </li>
        @if(auth::user()->user_type == 1)
        <li class="{{ Request::is('users*') ? 'active' : '' }}" >
            <a href="{{ url('/users') }}">
                <i class="material-icons">person</i>
                <span>Users</span>
            </a>
        </li>
        <li class="{{ Request::is('companies*') ? 'active' : '' }}" >
            <a href="{{ url('companies') }}">
                <i class="material-icons">location_city</i>
                <span>Clients/Companies</span>
            </a>
        </li>
        <li class="{{ Request::is( 'workshops*', 'designations*', 'expense-categories*', 'users*', 'taxes*', 'descriptions*', 'locations*', 'banks*', 'vendors*', 'expense-categories*', 'purchase-categories*', 'sub-purchase-categories*', 'asset-categories*', 'subassets*', 'claim-categories*') ? 'active' : '' }}">
            <a href="javascript:void(0); " class="menu-toggle">
                <i class="material-icons">perm_data_setting</i>
                <span>Manage</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Request::is('items*') ? 'active' : '' }}" >
                    <a href="{{ url('/items') }}">
                        <span>Items</span>
                    </a>
                </li>
                <li class="{{ Request::is('taxes*') ? 'active' : '' }}" >
                    <a href="{{ url('/taxes') }}">
                        <span>Taxes</span>
                    </a>
                </li>
                <li class="{{ Request::is( 'locations*') ? 'active' : '' }}" >
                    <a class="menu-toggle" >
                        <span>Seller</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{ Request::is('locations*') ? 'active' : '' }}" >
                            <a href="{{ url('locations') }}">
                                <span>Locations</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ Request::is('purchase-categories*', 'sub-purchase-categories*') ? 'active' : '' }}" >
                    <a class="menu-toggle" >
                        <span>Publication</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{ Request::is('purchase-categories*') ? 'active' : '' }}" >
                            <a href="{{ url('purchase-categories') }} ">
                                <span>Publications</span>
                            </a>
                        </li>
                            <li class="{{ Request::is('sub-purchase-categories*') ? 'active' : '' }}" >
                            <a href="{{ url('sub-purchase-categories') }} ">
                                <span>Series</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ Request::is('asset-categories*', 'subassets*') ? 'active' : '' }}" >
                    <a class="menu-toggle">
                        <span>Asset Categories</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{ Request::is('asset-categories*') ? 'active' : '' }}" >
                            <a href="{{ url('/asset-categories') }} ">
                                <span>Asset</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('subassets*') ? 'active' : '' }}" >
                            <a href="{{ url('/subassets') }} ">
                                <span>Sub Asset</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        @endif
        <li class="{{ Request::is('deposits*', 'user-transactions*') ? 'active' : '' }}" >
            <a href="{{ url('/deposits') }}" >
                <i class="material-icons">account_balance</i>
                <span>My Deposit</span>
            </a>
        </li>
        @if(auth::user()->user_type == 1 || auth::user()->user_type == 3 || auth::user()->user_type == 5)
        <li class="{{ Request::is('assets*') ? 'active' : '' }}">
            <a href="javascript:void(0); " class="menu-toggle">
                <i class="material-icons">business_center</i>
                <span>Assets</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Request::is('assets/new*') ? 'active' : '' }} ">
                    <a href="{{ url('/assets/new') }}" >
                        <span>New</span>
                    </a>
                </li>
                <li class="{{ Request::is('assets/old*') ? 'active' : '' }} ">
                    <a href="{{ url('/assets/old') }}" >
                        <span>Old</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</div>
<!-- #Menu -->