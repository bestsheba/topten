<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <style>
        .nav-item a[href*="pos"]:hover {
            transform: translateY(-2px);
        }

        /* Hide text on mobile, show only icons matching right panel style */
        @media (max-width: 768px) {
            .btn-text {
                display: none;
            }

            /* Reset Visit Website Border */
            .nav-item.border.rounded {
                border: none !important;
            }

            /* Reset Quick Sale Button to look like standard icon */
            .nav-item a[href*="pos"] {
                background: transparent !important;
                color: rgba(0, 0, 0, 0.5) !important;
                padding: 0.5rem 1rem !important;
            }

            /* Global nav link adjust for mobile */
            .nav-item .nav-link {
                padding-right: 1rem !important;
                padding-left: 1rem !important;
            }

            .nav-item.ml-2 {
                margin-left: 0 !important;
            }
        }
    </style>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        {{-- <li class="nav-item border rounded mr-2">
            <a class="nav-link" href="{{ url('/') }}" target="_blank" role="button">
                <i class="fas fa-globe mr-1"></i> <span class="btn-text">Visit Website</span>
            </a>
        </li>
        <li class="nav-item mr-2">
            <a class="nav-link btn btn-sm px-3 py-2 text-white font-weight-bold" href="{{ route('admin.pos') }}"
                role="button"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                      border-radius: 20px; 
                      transition: all 0.3s ease;">
                <i class="fas fa-cart-plus mr-1"></i> <span class="btn-text">Quick Sale</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link text-white font-weight-bold mr-2" href="{{ route('admin.tailor.orders.create') }}"
            role="button"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                      border-radius: 20px; 
                      transition: all 0.3s ease;">
                <i class="fas fa-cart-plus"></i><span class="ml-1 btn-text">Service Order</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white font-weight-bold" href="{{ route('admin.pos') }}"
            role="button"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                      border-radius: 20px; 
                      transition: all 0.3s ease;">
                <i class="fas fa-cart-plus"></i><span class="ml-1 btn-text">Product Order</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}" target="_blank" role="button">
                <i class="fas fa-globe"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.profile') }}" class="nav-link">
                <i class="fas fa-user mr-2 user-image img-circle" style="font-size: 1.2rem;"></i>
            </a>
        </li>
    </ul>
</nav>
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
    @csrf
</form>
