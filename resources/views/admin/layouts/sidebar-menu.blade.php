<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @can('view dashboard')
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
    @endcan

    @canany(['view products', 'view categories', 'view brands', 'manage sizes', 'manage colors'])
        <li
            class="nav-item {{ request()->routeIs('admin.product.*') || request()->routeIs('admin.size.*') || request()->routeIs('admin.color.*') || request()->routeIs('admin.brand.*') || request()->routeIs('admin.category.*') || request()->routeIs('admin.sub-category.*') ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ request()->routeIs('admin.product.*') || request()->routeIs('admin.size.*') || request()->routeIs('admin.color.*') || request()->routeIs('admin.brand.*') || request()->routeIs('admin.category.*') || request()->routeIs('admin.sub-category.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-box"></i>
                <p>
                    Invertory
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('view products')
                    <li class="nav-item">
                        <a href="{{ route('admin.product.index') }}"
                            class="nav-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Product Manage</p>
                        </a>
                    </li>
                @endcan

                @can('view categories')
                    <li class="nav-item">
                        <a href="{{ route('admin.category.index') }}"
                            class="nav-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Category</p>
                        </a>
                    </li>
                @endcan

                @can('manage subcategories')
                    <li class="nav-item">
                        <a href="{{ route('admin.sub-category.index') }}"
                            class="nav-link {{ request()->routeIs('admin.sub-category.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Sub Category</p>
                        </a>
                    </li>
                @endcan

                @can('view brands')
                    <li class="nav-item">
                        <a href="{{ route('admin.brand.index') }}"
                            class="nav-link {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tag"></i>
                            <p>Brand</p>
                        </a>
                    </li>
                @endcan
                @can('manage sizes')
                    <li class="nav-item">
                        <a href="{{ route('admin.attributes.index') }}"
                            class="nav-link {{ request()->routeIs('admin.attributes.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Attributes</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany
    @can('view dashboard')
        <li class="nav-item">
            <a href="{{ route('admin.services.index') }}"
                class="nav-link {{ request()->routeIs('admin.services.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Services
                </p>
            </a>
        </li>
    @endcan
    @canany(['view orders', 'manage incomplete orders'])
        <li
            class="nav-item {{ request()->routeIs('admin.order*') || request()->routeIs('admin.pos') ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ request()->routeIs('admin.order.*') || request()->routeIs('admin.pos') ? 'active' : '' }}">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                    Order
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('view orders')
                    <li class="nav-item">
                        <a href="{{ route('admin.pos') }}"
                            class="nav-link {{ request()->routeIs('admin.pos') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                POS
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view orders')
                    <li class="nav-item">
                        <a href="{{ route('admin.order.index') }}"
                            class="nav-link {{ request()->routeIs('admin.order.*') && !request()->routeIs('admin.order.incomplete') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                All Order
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @can('view customers')
        <li class="nav-item">
            <a href="{{ route('admin.customers.index') }}"
                class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Customer
                </p>
            </a>
        </li>
    @endcan

    @canany(['manage settings', 'manage site colors'])
        <li
            class="nav-item {{ (request()->routeIs('admin.settings') && request()->segment(3) != 'affiliate') || request()->routeIs('admin.sliders.*') || request()->routeIs('admin.offer-slider.*') ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ (request()->routeIs('admin.settings') && request()->segment(3) != 'affiliate') || request()->routeIs('admin.sliders.*') || request()->routeIs('admin.offer-slider.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    Settings
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('manage settings')
                    <li
                        class="nav-item {{ (request()->routeIs('admin.settings') && request()->segment(3) == 'home') || request()->routeIs('admin.sliders.*') || request()->routeIs('admin.offer-slider.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ (request()->routeIs('admin.settings') && request()->segment(3) == 'home') || request()->routeIs('admin.sliders.*') || request()->routeIs('admin.offer-slider.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Home Page Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.settings', 'home') }}"
                                    class="nav-link {{ request()->routeIs('admin.settings') && request()->segment(3) == 'home' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-sliders-h"></i>
                                    <p>General Settings</p>
                                </a>
                            </li>
                            @can('manage sliders')
                                <li class="nav-item">
                                    <a href="{{ route('admin.sliders.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-image"></i>
                                        <p>Slider</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.settings', 'footer') }}"
                            class="nav-link {{ request()->routeIs('admin.settings') && request()->segment(3) == 'footer' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                Footer Setting
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany
    @canany(['manage expense'])
        <li
            class="nav-item {{ request()->routeIs('admin.expense-types.*') || request()->routeIs('admin.expenses.*') ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ request()->routeIs('admin.expense-types.*') || request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-wallet"></i>
                <p>
                    Expenses
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.expense-types.index') }}"
                        class="nav-link {{ request()->routeIs('admin.expense-types.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Expense Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.expenses.index') }}"
                        class="nav-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Expenses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.expenses.create') }}"
                        class="nav-link {{ request()->routeIs('admin.expenses.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create Expense</p>
                    </a>
                </li>
            </ul>
        </li>
    @endcanany
    @canany(['manage measurement'])
        <li
            class="nav-item {{ request()->routeIs('admin.garment-types.*') || request()->routeIs('admin.expenses.*') ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ request()->routeIs('admin.garment-types.*') || request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-wallet"></i>
                <p>
                    Measurement
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.garment-types.index') }}"
                        class="nav-link {{ request()->routeIs('admin.garment-types.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Types</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.measurement-fields.index') }}"
                        class="nav-link {{ request()->routeIs('admin.measurement-fields.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Fields</p>
                    </a>
                </li>
            </ul>
        </li>
    @endcanany

    @can('view roles')
        <li class="nav-item">
            <a href="{{ route('admin.role.index') }}"
                class="nav-link {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                    Role Management
                </p>
            </a>
        </li>
    @endcan

    @can('view admins')
        <li class="nav-item">
            <a href="{{ route('admin.admin.index') }}"
                class="nav-link {{ request()->routeIs('admin.admin.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                    Admin Management
                </p>
            </a>
        </li>
    @endcan

    <li class="nav-item">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
                Logout
            </p>
        </a>
    </li>
</ul>

<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
    @csrf
</form>
