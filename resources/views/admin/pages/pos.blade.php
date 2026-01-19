@extends('admin.layouts.app')

@section('title', 'POS Terminal')

@section('css')
    <style> 
        /* ===== Modern POS System Design ===== */

        /* Full screen layout */
        html,
        body {
            height: 100%;
            overflow: hidden;
            background: #f5f7fc;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 60px);
            overflow: hidden;
            background: #f5f7fc;
        }

        .content-header {
            flex-shrink: 0;
            padding: 0.5rem 0;
            margin-bottom: 0;
        }

        /* Main container */
        .pos-main-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 60px);
            padding: 0.5rem;
            gap: 1rem;
            overflow: hidden;
            background: #ffffff;
        }

        /* Hero Section - Modern Dashboard Stats */
        .pos-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.25);
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .pos-hero::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            z-index: 0;
        }

        .pos-hero::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -30px;
            left: -30px;
            z-index: 0;
        }

        .pos-hero>* {
            position: relative;
            z-index: 1;
        }

        .pos-hero h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1.1;
        }

        .pos-hero h4 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0;
            line-height: 1;
            opacity: 0.95;
        }

        .pos-hero p {
            font-size: 13px;
            margin-bottom: 0;
            opacity: 0.9;
            line-height: 1.2;
            font-weight: 500;
        }

        /* Content row */
        .pos-content-row {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        /* Cards - Modern styling */
        .pos-catalog,
        .pos-cart,
        .pos-customer-card {
            border-radius: 16px;
            border: 1px solid #b6b6b6b3;
            background: #fff;
            /* box-shadow: 0 8px 32px rgba(10, 29, 58, 0.08); */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .pos-catalog {
            flex: 1.8;
            min-width: 0;
        }

        .pos-cart {
            flex: 1.2;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .pos-customer-card {
            flex: 1;
            min-width: 0;
        }

        .pos-catalog .card-body,
        .pos-cart .card-body,
        .pos-customer-card .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex: 1;
            gap: 12px;
        }

        .pos-cart .card-header,
        .pos-customer-card .card-header {
            padding: 16px;
            border-bottom: 1px solid #f0f2f7;
            flex-shrink: 0;
            background: linear-gradient(135deg, #f5f7fc 0%, #fafbff 100%);
        }

        /* Search bar - Modern */
        .pos-search input {
            border-radius: 12px;
            border: 2px solid #e8ecf4;
            font-size: 14px;
            height: 40px;
            /* padding: 8px 16px 8px 42px; */
            background: #f8faff;
            transition: all 0.3s ease;
        }

        .pos-search input:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .pos-search .input-group-text {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            border: 2px solid #e8ecf4;
            border-right: none;
            background: transparent;
            color: #9ca3af;
            padding: 8px 12px;
            font-size: 14px;
        }

        /* Category pills */
        .category-pills-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 6px;
            margin-bottom: 8px;
            overflow-x: auto;
            overflow-y: hidden;
            flex-shrink: 0;
            padding: 2px 0;
            padding-bottom: 6px;
        }

        .category-pill {
            border-radius: 16px;
            padding: 5px 12px;
            border: 1px solid #e8ecf4;
            background: #fff;
            color: #4b5563;
            font-weight: 500;
            font-size: 11px;
            transition: all 0.3s ease;
            flex-shrink: 0;
            cursor: pointer;
        }

        .category-pill:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .category-pill.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
        }

        /* Products container */
        .products-container {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            margin-bottom: 12px;
            padding-right: 4px;
        }

        .products-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        /* Product cards - Enhanced */
        .product-card {
            border-radius: 12px;
            border: 1px solid #e8ecf4;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(10, 29, 58, 0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }

        .product-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-bottom: 1px solid #f0f2f7;
        }

        .product-card__body {
            padding: 8px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card__body h5 {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 2px;
            line-height: 1.2;
            color: #1f2937;
        }

        .product-card__body .text-muted {
            font-size: 10px;
            margin-bottom: 4px;
            color: #9ca3af;
        }

        .product-pricing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 4px;
            gap: 4px;
        }

        .product-pricing h5 {
            font-size: 12px;
            font-weight: 700;
            margin: 0;
            color: #667eea;
        }

        .product-pricing .btn {
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        /* Cart items */
        /* .cart-items-container {
            flex: 1;
            min-height: 200px;
            overflow-y: auto;
            margin-bottom: 12px;
            padding-right: 6px;
        } */

        .cart-item {
            border-radius: 12px;
            border: 1px solid #e8ecf4;
            padding: 12px;
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #f8faff 0%, #fafbff 100%);
            font-size: 12px;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .cart-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .cart-item h6 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2px;
            color: #1f2937;
        }

        .cart-item .text-muted {
            font-size: 11px;
            color: #9ca3af;
        }

        .cart-qty {
            border-radius: 8px;
            background: #fff;
            border: 1px solid #e8ecf4;
            overflow: hidden;
            display: inline-flex;
            box-shadow: 0 2px 4px rgba(10, 29, 58, 0.05);
        }

        .cart-qty .btn {
            padding: 4px 8px;
            font-size: 11px;
            border: none;
            min-width: 24px;
            background: #f8faff;
            color: #667eea;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .cart-qty .btn:hover {
            background: #667eea;
            color: #fff;
        }

        .cart-qty input {
            width: 35px;
            padding: 4px;
            font-size: 12px;
            text-align: center;
            border: none;
            background: #fff;
            font-weight: 600;
        }

        /* Totals section */
        .totals-section {
            flex-shrink: 0;
            border-top: 2px solid #e8ecf4;
            padding: 12px;
            border-radius: 12px;
            margin-top: 0;
            background: linear-gradient(135deg, #f8faff 0%, #fafbff 100%);
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 13px;
            padding: 4px 0;
            color: #4b5563;
        }

        .totals-row:last-child {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #e8ecf4;
        }

        /* Customer form */
        .customer-form-section {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding-right: 4px;
        }

        .customer-suggestion {
            border-bottom: 1px solid #f0f2f7;
            padding: 8px;
            cursor: pointer;
            font-size: 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .customer-suggestion:last-child {
            border-bottom: none;
        }

        .customer-suggestion:hover {
            color: #667eea;
            background: #f8faff;
        }

        .customer-suggestion .text-muted {
            font-size: 11px;
            color: #9ca3af;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e8ecf4;
            height: 36px;
            background: #f8faff;
            transition: all 0.3s ease;
        }

        textarea.form-control {
            height: auto;
            min-height: 48px;
            resize: none;
        }

        .form-control:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Payment method chips */
        .pos-chip {
            border-radius: 10px;
            padding: 6px 14px;
            border: 2px solid #e8ecf4;
            color: #4b5563;
            margin-right: 6px;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            background: #fff;
        }

        .pos-chip:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .pos-chip.bg-dark {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: transparent !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Buttons */
        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-secondary.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Product Add Button States */
        .pos-add-product-btn {
            transition: all 0.3s ease;
        }

        .pos-add-product-btn.btn-dark {
            background: #1f2937;
            border-color: #1f2937;
            color: #fff;
        }

        .pos-add-product-btn.btn-dark:hover {
            background: #374151;
            border-color: #374151;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .pos-add-product-btn.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .pos-add-product-btn.btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-lg {
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            height: 44px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-block {
            width: 100%;
            flex-shrink: 0;
            margin-top: 12px;
        }

        /* Input groups */
        .input-group-prepend .btn,
        .input-group-append .btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .input-group-text {
            font-size: 12px;
            padding: 8px 12px;
            background: #f8faff;
            border: 1px solid #e8ecf4;
        }

        /* Badges */
        .badge {
            font-size: 10px;
            padding: 3px 8px;
            font-weight: 500;
            border-radius: 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Card headers */
        .card-header h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 0;
            color: #1f2937;
        }

        .card-header small {
            font-size: 11px;
            color: #9ca3af;
        }

        .card-header h5 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 0;
            color: #1f2937;
        }

        /* Alerts */
        .alert {
            padding: 10px 14px;
            font-size: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
        }

        /* Pagination */
        .pagination {
            margin: 0;
            gap: 3px;
        }

        .pagination .page-link {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid #e8ecf4;
            color: #667eea;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
        }

        .pagination .page-link.active {
            background: #667eea;
            border-color: #667eea;
        }

        /* Scrollbar styling */
        .products-container::-webkit-scrollbar,
        .cart-items-container::-webkit-scrollbar,
        .customer-form-section::-webkit-scrollbar {
            width: 6px;
        }

        .products-container::-webkit-scrollbar-track,
        .cart-items-container::-webkit-scrollbar-track,
        .customer-form-section::-webkit-scrollbar-track {
            background: transparent;
        }

        .products-container::-webkit-scrollbar-thumb,
        .cart-items-container::-webkit-scrollbar-thumb,
        .customer-form-section::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .products-container::-webkit-scrollbar-thumb:hover,
        .cart-items-container::-webkit-scrollbar-thumb:hover,
        .customer-form-section::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Category pills scrollbar */
        .category-pills-container::-webkit-scrollbar {
            height: 4px;
        }

        .category-pills-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .category-pills-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .category-pills-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Pagination info */
        .pagination-info {
            font-size: 12px;
            flex-shrink: 0;
            margin-top: 8px;
            color: #6b7280;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .pos-catalog {
                flex: 1.5;
            }

            .pos-cart {
                flex: 1;
            }

            .pos-customer-card {
                flex: 0.9;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .pos-content-row {
                flex-direction: column;
                gap: 0.75rem;
            }

            .pos-catalog,
            .pos-cart,
            .pos-customer-card {
                flex: 1;
                min-height: 0;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            }

            .pos-main-container {
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .pos-hero {
                padding: 16px;
            }

            .pos-hero h2 {
                font-size: 24px;
            }

            .pos-hero h4 {
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {

            html,
            body {
                height: auto;
                overflow: auto;
            }

            .content-wrapper {
                height: auto;
                overflow: auto;
            }

            .pos-main-container {
                height: auto;
                min-height: 100vh;
                overflow: auto;
                padding: 0.5rem;
                gap: 0.5rem;
            }

            .pos-content-row {
                min-height: auto;
            }

            .pos-hero {
                padding: 12px;
            }

            .pos-hero h2 {
                font-size: 20px;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 8px;
            }

            .product-card img {
                height: 100px;
            }

            .cart-item {
                padding: 8px;
                gap: 8px;
            }

            .cart-item img {
                width: 45px;
                height: 45px;
            }

            .pos-catalog .card-body,
            .pos-cart .card-body,
            .pos-customer-card .card-body {
                padding: 12px;
            }

            .pos-cart .card-header,
            .pos-customer-card .card-header {
                padding: 12px;
            }

            .card-header h4 {
                font-size: 14px;
            }

            .form-control {
                font-size: 12px;
                height: 34px;
                padding: 6px 10px;
            }

            .btn-sm {
                padding: 3px 8px;
                font-size: 11px;
            }

            .pos-chip {
                font-size: 11px;
                padding: 4px 10px;
            }
        }

        @media (max-width: 480px) {
            .pos-main-container {
                padding: 0.5rem;
                gap: 0.5rem;
            }

            .pos-content-row {
                gap: 0.5rem;
            }

            .pos-hero {
                padding: 12px;
            }

            .pos-hero h2 {
                font-size: 18px;
            }

            .pos-hero h4 {
                font-size: 11px;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(85px, 1fr));
                gap: 6px;
            }

            .product-card img {
                height: 80px;
            }

            .product-card__body {
                padding: 6px;
            }

            .product-card__body h5 {
                font-size: 10px;
            }

            .cart-item {
                padding: 6px;
                gap: 6px;
                font-size: 11px;
                margin-bottom: 4px;
            }

            .cart-item img {
                width: 40px;
                height: 40px;
            }

            .cart-item h6 {
                font-size: 11px;
            }

            .pos-chip {
                font-size: 10px;
                padding: 3px 8px;
                margin-right: 4px;
                margin-bottom: 4px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            .form-control {
                font-size: 11px;
                height: 32px;
                padding: 5px 8px;
            }

            .btn-lg {
                padding: 10px 14px;
                font-size: 12px;
                height: 40px;
            }

            .alert {
                padding: 8px 10px;
                font-size: 11px;
            }

            .card-header h4 {
                font-size: 13px;
            }

            .card-header h5 {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('page')
    @livewire('admin.pos-panel')
@endsection

@section('script')
    <script>
        $('body').addClass('sidebar-collapse');
    </script>
@endsection
