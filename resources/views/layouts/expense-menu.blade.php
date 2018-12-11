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
        <li style="display:none" class="{{ Request::is('users/'.Auth::id().'/edit') ? 'active' : '' }}" >
            <a href="{{ url('/users') }}">
                <i class="material-icons">perm_data_setting</i>
                <span>Users</span>
            </a>
        </li>
        @if(auth::user()->user_type == 3)
        <li class="{{ Request::is('users*') ? 'active' : '' }}" >
            <a href="{{ url('/users') }}">
                <i class="material-icons">perm_data_setting</i>
                <span>Users</span>
            </a>
        </li>
        @endif
        @if(auth::user()->user_type == 1)
        <li class="{{ Request::is('companies*', 'workshops*', 'designations*', 'expense-categories*', 'users*', 'taxes*', 'descriptions*', 'locations*', 'banks*', 'vendors*', 'expense-categories*', 'purchase-categories*', 'sub-purchase-categories*', 'asset-categories*', 'subassets*', 'claim-categories*') ? 'active' : '' }}">
            <a href="javascript:void(0); " class="menu-toggle">
                <i class="material-icons">perm_data_setting</i>
                <span>Manage</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Request::is('companies*') ? 'active' : '' }}" >
                    <a href="{{ url('companies') }}">
                        <span>Companies</span>
                    </a>
                </li>
                <li class="{{ Request::is('locations*') ? 'active' : '' }}" >
                    <a href="{{ url('locations') }}">
                        <span>Locations</span>
                    </a>
                </li>
                <li class="{{ Request::is('users*') ? 'active' : '' }}" >
                    <a href="{{ url('/users') }}">
                        <span>Users</span>
                    </a>
                </li>
                <li class="{{ Request::is('items*') ? 'active' : '' }}" >
                    <a href="{{ url('/items') }}">
                        <span>Items</span>
                    </a>
                </li>
                <li class="{{ Request::is('designations*') ? 'active' : '' }}" >
                    <a href="{{ url('/designations') }}">
                        <span>Designations</span>
                    </a>
                </li>
                <li class="{{ Request::is('taxes*') ? 'active' : '' }}" >
                    <a href="{{ url('/taxes') }}">
                        <span>Taxes</span>
                    </a>
                </li>
                <li class="{{ Request::is('banks*') ? 'active' : '' }}" >
                    <a href="{{ url('/banks') }}">
                        <span>Company Banks</span>
                    </a>
                </li>
                <li class="{{ Request::is('vendors*') ? 'active' : '' }}" >
                    <a href="{{ url('/vendors') }}">
                        <span>Vendors</span>
                    </a>
                </li>
                <li class="{{ Request::is('claim-categories*') ? 'active' : '' }}" >
                    <a href="{{ url('/claim-categories') }}">
                        <span>Claim Form Categories</span>
                    </a>
                </li>
                <li class="{{ Request::is('sub-claim-categories*') ? 'active' : '' }}" >
                    <a href="{{ url('/sub-claim-categories') }}">
                        <span>Claim Form Sub Categories</span>
                    </a>
                </li>
                <li class="{{ Request::is('purchase-categories*', 'sub-purchase-categories*') ? 'active' : '' }}" >
                    <a class="menu-toggle" >
                        <span>Publication Categories</span>
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
                </li>
                <li class="{{ Request::is('expense-categories*') ? 'active' : '' }}" >
                    <a class="menu-toggle" >
                        <span>Expense Categories</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{ Request::is('expense-categories*') ? 'active' : '' }}" >
                            <a href="{{ url('expense-categories') }} ">
                                <span>Expense Category</span>
                            </a>
                        </li>
                            <li class="{{ Request::is('sub-expense-categories*') ? 'active' : '' }}" >
                            <a href="{{ url('sub-expense-categories') }} ">
                                <span>Sub Category</span>
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
        @if(auth::user()->user_type != 5)
        <li class="{{ Request::is('deposits*', 'user-transactions*') ? 'active' : '' }}" >
            <a href="{{ url('/deposits') }}" >
                <i class="material-icons">account_balance</i>
                <span> @if(auth::user()->user_type == 4 ) Share My  @endif Deposit</span>
            </a>
        </li>
        @endif
        <li class="{{ Request::is('expenses*') ? 'active' : '' }}" >
            <a href="{{ url('/expenses') }}" >
                <i class="material-icons">shopping_basket</i>
                <span>Expense</span>
            </a>
        </li>
        <li class="{{ Request::is('received-payments*') ? 'active' : '' }}">
            <a href="{{ url('/received-payments') }}" >
                <i class="material-icons">local_atm</i>
                <span>Received Payments</span>
            </a>
        </li>
        <li class="{{ Request::is('payment-vendors*') ? 'active' : '' }}" >
            <a href="{{ url('/payment-vendors') }}" >
                <i class="material-icons">chrome_reader_mode</i>
                <span>Vendor Acc Statement</span>
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
        <li class="{{ Request::is('report*' ) ? 'active' : '' }}" >
            <a href="javascript:void(0); " class="menu-toggle">
                <i class="material-icons">developer_board</i>
                <span>Report</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Request::is('report/expenses*') ? 'active' : '' }}" >
                    <a href="{{ url('/report/expenses') }}">
                        <span>Expense</span>
                    </a>
                </li>
                <li class="{{ Request::is('report/received-payments*') ? 'active' : '' }}" >
                    <a href="{{ url('/report/received-payments') }}">
                        <span>Cheque</span>
                    </a>
                </li>
                <li class="{{ Request::is('report/deposits*') ? 'active' : '' }}" >
                    <a href="{{ url('/report/deposits') }}">
                        <span>Deposit</span>
                    </a>
                </li>
                <li class="{{ Request::is('report/vendor-tds') ? 'active' : '' }}" >
                    <a href="{{ url('/report/vendor-tds') }}">
                        <span>Vendor TDS</span>
                    </a>
                </li>
                @if(auth::user()->user_type == 1 || auth::user()->user_type == 3 || auth::user()->user_type == 5)
                <li class="{{ Request::is('report/assets') ? 'active' : '' }}" >
                    <a href="{{ url('/report/assets') }}">
                        <span>Asset</span>
                    </a>
                </li>
                <li class="{{ Request::is('report/claims') ? 'active' : '' }}" >
                    <a href="{{ url('/report/claims') }}">
                        <span>Claim</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
    </ul>
</div>
<!-- #Menu -->