@extends('admin.layouts.app')

@section('title')
    Orders
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Orders
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="admin">
                                Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Orders
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <style>
                /* Compact stats: reduce column gutters, card padding and number size */
                .compact-stats {
                    margin-left: -6px;
                    margin-right: -6px;
                }

                .compact-stats>.col-md-3,
                .compact-stats>.col-sm-6 {
                    padding-left: 6px;
                    padding-right: 6px;
                }

                .compact-stats .small-box {
                    padding: .5rem 0.6rem;
                    margin-bottom: 8px;
                }

                /* Slightly smaller stat numbers for compact view */
                .compact-stats .small-box .inner h5 {
                    font-size: 1rem;
                    /* ~16px */
                    margin-bottom: 0.25rem;
                }

                /* Make stat cards visually clickable */
                .small-box.clickable {
                    cursor: pointer;
                }

                /* Tighten the small label spacing */
                .compact-stats .small-box .inner small {
                    display: block;
                    line-height: 1;
                    font-size: .75rem;
                }
            </style>

            <div class="row compact-stats">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'total_orders'])) }}"
                        class="small-box bg-info p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ number_format($stats['total_orders'] ?? 0) }}</h5>
                            <small>Total Orders</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'total_revenue'])) }}"
                        class="small-box bg-success p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ showAmount($stats['total_revenue'] ?? 0) }}</h5>
                            <small>Total Revenue</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'today_orders'])) }}"
                        class="small-box bg-warning p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ number_format($stats['today_orders'] ?? 0) }}</h5>
                            <small>Today's Orders</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'this_month_orders'])) }}"
                        class="small-box bg-secondary p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ number_format($stats['this_month_orders'] ?? 0) }}</h5>
                            <small>This Month</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'pending'])) }}"
                        class="small-box bg-danger p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ number_format($stats['pending'] ?? 0) }}</h5>
                            <small>Pending Orders</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'average_order_value'])) }}"
                        class="small-box bg-dark p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ showAmount($stats['average_order_value'] ?? 0) }}</h5>
                            <small>Avg Order Value</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'completed'])) }}"
                        class="small-box bg-success p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">
                                {{ number_format($stats['completed'] ?? ($stats['delivered'] ?? 0)) }}</h5>
                            <small>Completed Orders</small>
                        </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'courier_sent'])) }}"
                        class="small-box bg-primary p-2 text-white clickable d-block text-center">
                        <div class="inner text-center">
                            <h5 class="mb-1">{{ number_format($stats['courier_sent'] ?? 0) }}</h5>
                            <small>Courier Sent</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <!-- Left side with search -->
                                <div class="d-md-flex flex-grow-1 flex-wrap align-items-center gap-2">
                                    <h3 class="card-title mb-0 mr-2">
                                        Orders
                                    </h3>
                                    <form action="{{ route('admin.order.index') }}" class="search-form flex-grow-1"
                                        style="max-width: 300px;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                                class="form-control" placeholder="Id, Customer Name, Phone">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Right side with courier options -->
                                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                    <div class="input-group input-group-sm mr-2">
                                        <select class="form-control"
                                            onchange="if(this.value) window.location.href=this.value"
                                            style="min-width: 140px;">
                                            <option
                                                value="{{ route('admin.order.index', request()->except(['payment_status', 'page'])) }}">
                                                Payment Status</option>
                                            <option
                                                value="{{ route('admin.order.index', array_merge(request()->except(['payment_status', 'page']), ['payment_status' => 'paid'])) }}"
                                                {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option
                                                value="{{ route('admin.order.index', array_merge(request()->except(['payment_status', 'page']), ['payment_status' => 'due'])) }}"
                                                {{ request('payment_status') == 'due' ? 'selected' : '' }}>Due (Unpaid)
                                            </option>
                                            <option
                                                value="{{ route('admin.order.index', array_merge(request()->except(['payment_status', 'page']), ['payment_status' => 'partial'])) }}"
                                                {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-group input-group-sm mr-1">
                                        <select id="courierSelect" class="form-control" style="min-width: 120px;">
                                            <option value="pathao">Pathao</option>
                                            <option value="steadfast">Steadfast</option>
                                        </select>
                                    </div>
                                    <button type="button"
                                        class="btn btn-success btn-sm d-inline-flex align-items-center gap-1"
                                        onclick="openCourierModal()" id="courierBtn" disabled style="white-space: nowrap">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span id="courierBtnText">Send</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="w-100 mb-2 align-items-center gap-2 bulk-actions" style="display: none;">
                                    <button class="btn btn-danger btn-sm" onclick="bulkDelete()"
                                        style="white-space: nowrap">
                                        <i class="fas fa-trash"></i> Bulk Delete (<span class="selected-count">0</span>)
                                    </button>
                                    <div class="input-group input-group-sm ml-2" style="width: 250px;">
                                        <select id="bulkStatusSelect" class="form-control select2"
                                            data-theme="bootstrap">
                                            <option value="">Change Status</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Confirmed</option>
                                            <option value="3">Packaging</option>
                                            <option value="4">Out for delivery</option>
                                            <option value="5">Delivered</option>
                                            <option value="6">Canceled</option>
                                            <option value="7">Returned</option>
                                            <option value="8">Failed to delivery</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" onclick="bulkStatusChange()">
                                                Update Status
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-hover text-nowrap table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5">
                                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                            </th>
                                            <th width="5">Order ID</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Paid Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment Status</th>
                                            <th>Transaction Id</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Courier</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="order-checkbox"
                                                        value="{{ $order->id }}" onchange="updateButtons()">
                                                </td>
                                                <td>
                                                    <span
                                                        id="order-id-{{ $order->id }}">{{ $order->hashed_id }}</span>
                                                    <button class="btn btn-sm btn-light copy-order-id"
                                                        data-order-id="order-id-{{ $order->id }}"
                                                        title="Copy Order ID">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">
                                                        {{ $order->customer_name }}
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ $order->customer_phone_number }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">
                                                        {{ showAmount($order->total) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $paidAmount = $order->paid_amount ?? 0;
                                                        $dueAmount = $order->total - $paidAmount;
                                                        $colorClass = 'text-danger';
                                                        if ($paidAmount >= $order->total) {
                                                            $colorClass = 'text-success';
                                                        } elseif ($paidAmount > 0) {
                                                            $colorClass = 'text-warning';
                                                        }
                                                    @endphp
                                                    <div class="font-weight-bold {{ $colorClass }}">
                                                        {{ showAmount($paidAmount) }}
                                                        @if ($dueAmount > 0)
                                                            <small class="d-block text-muted">
                                                                Due: {{ showAmount($dueAmount) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ Str::ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ Str::ucfirst(str_replace('_', ' ', $order->payment_status ?? 'due')) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $order->payment_transaction_id ?? '-' }}
                                                </td>
                                                <td>
                                                    {!! $order->source_badge !!}
                                                </td>
                                                <td>
                                                    {!! $order->status_b_value !!}
                                                </td>
                                                <td>
                                                    @if ($order->courier)
                                                        <div>
                                                            <span
                                                                class="badge badge-info">{{ ucfirst($order->courier) }}</span>
                                                            @if (strtolower($order->courier) === strtolower(App\Enums\CourierList::PATHAO->value))
                                                                @php
                                                                    $pathaoOrder = $order->pathaoOrder;
                                                                @endphp
                                                                @if ($pathaoOrder)
                                                                    <div class="mt-1">
                                                                        <small class="text-muted d-block">Status:
                                                                            {{ $pathaoOrder->order_status }}</small>
                                                                        <form
                                                                            action="{{ route('admin.pathao.orders.sync-status', $order->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            <input type="hidden" name="consignment_id"
                                                                                value="{{ $pathaoOrder->consignment_id }}">
                                                                            <button type="submit"
                                                                                class="btn btn-xs btn-outline-info mt-1">
                                                                                <i class="fas fa-sync"></i> Sync Status
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex items-align-center">
                                                        @if ($order->payment_status !== 'paid')
                                                            <button type="button" class="btn btn-warning mr-2"
                                                                onclick="openPaymentModal({{ $order->id }}, '{{ $order->hashed_id }}', {{ $order->user_id ?? 'null' }}, '{{ $order->user->name ?? $order->customer_name }}', {{ $order->total }}, {{ $order->paid_amount ?? 0 }})">
                                                                Payment
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('admin.order.show', $order->id) }}"
                                                            class="btn btn-info mr-2">
                                                            Invoice
                                                        </a>
                                                        <a href="{{ route('admin.order.edit', $order->id) }}"
                                                            class="btn btn-primary mr-2">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('admin.order.destroy', $order->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Are you sure?')"
                                                                class="btn btn-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="14" class="text-center">
                                                    No Data ....
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Create Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paymentForm" action="{{ route('admin.order.payment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" id="paymentOrderId">
                    <input type="hidden" name="user_id" id="paymentUserId">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="paymentCreatedAt">Created At</label>
                            <input type="datetime-local" class="form-control" id="paymentCreatedAt" name="created_at"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Select User</label>
                            <div class="form-control bg-light" id="selectedUserName" style="cursor: not-allowed;">
                                <!-- User name will be populated here -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Select Order</label>
                            <div class="form-control bg-light" id="selectedOrderId" style="cursor: not-allowed;">
                                <!-- Order ID will be populated here -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="paymentTransactionId">Transaction ID (Optional)</label>
                            <input type="text" class="form-control" id="paymentTransactionId" name="transaction_id"
                                placeholder="Enter Transaction ID">
                        </div>

                        <div class="form-group">
                            <label for="paymentAmount">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="paymentAmount" name="amount"
                                placeholder="Enter Amount" required min="0.01">
                            <small class="form-text text-muted">
                                DUE AMOUNT: <span id="dueAmountDisplay" class="font-weight-bold">0</span>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="paymentMethod">Payment Method</label>
                            <input type="text" class="form-control" id="paymentMethod" name="payment_method"
                                placeholder="Enter Payment Method" required>
                            <small class="form-text">
                                CLICK TO INSERT
                                <a href="javascript:void(0)" onclick="insertPaymentMethod('BKASH')"
                                    class="text-primary font-weight-bold">BKASH</a>,
                                <a href="javascript:void(0)" onclick="insertPaymentMethod('NAGAD')"
                                    class="text-primary font-weight-bold">NAGAD</a>,
                                <a href="javascript:void(0)" onclick="insertPaymentMethod('ROCKET')"
                                    class="text-primary font-weight-bold">ROCKET</a>,
                                <a href="javascript:void(0)" onclick="insertPaymentMethod('BANK')"
                                    class="text-primary font-weight-bold">BANK</a>,
                                <a href="javascript:void(0)" onclick="insertPaymentMethod('CASH')"
                                    class="text-primary font-weight-bold">CASH</a>
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitPayment()">Create Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Courier Form -->
    <form id="courierForm" action="#" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds">
        <div id="courierFormInputs"></div>
    </form>

    <script>
        // Payment Modal Variables - Declare at the top
        var currentOrderId = null;
        var currentOrderHashedId = null;
        var currentUserId = null;
        var currentUserName = null;
        var currentOrderTotal = 0;
        var currentPaidAmount = 0;

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.order-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateButtons();
        }

        function updateButtons() {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            const courierBtn = document.getElementById('courierBtn');
            const bulkActions = document.querySelector('.bulk-actions');
            const selectedCountSpan = document.querySelector('.selected-count');
            const courier = document.getElementById('courierSelect') ? document.getElementById('courierSelect').value :
                'pathao';
            const courierLabel = courier === 'steadfast' ? '' : '';

            // Update selected count
            selectedCountSpan.textContent = checkboxes.length;

            // Show/hide bulk actions
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
                bulkActions.classList.add('d-flex');
                courierBtn.disabled = false;
                courierBtn.innerHTML = `<i class="fas fa-shipping-fast"></i> Send ${courierLabel} (${checkboxes.length})`;
            } else {
                bulkActions.style.display = 'none';
                bulkActions.classList.remove('d-flex');
                courierBtn.disabled = true;
                courierBtn.innerHTML = `<i class="fas fa-shipping-fast"></i> Send ${courierLabel}`;
            }
        }

        function updateCourierButton() {
            updateButtons();
        }

        function getSelectedOrderIds() {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function bulkDelete() {
            const selectedOrders = getSelectedOrderIds();

            if (selectedOrders.length === 0) {
                alert('Please select orders to delete');
                return;
            }

            if (!confirm('Are you sure you want to delete the selected orders?')) {
                return;
            }

            // Create and submit form for bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.order.bulk-delete') }}';

            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Method field
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Add selected order IDs
            selectedOrders.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkStatusChange() {
            const selectedOrders = getSelectedOrderIds();
            const newStatus = document.getElementById('bulkStatusSelect').value;

            if (selectedOrders.length === 0) {
                alert('Please select orders to update');
                return;
            }

            if (!newStatus) {
                alert('Please select a status');
                return;
            }

            // Create and submit form for bulk status update
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.order.bulk-status-update') }}';

            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add selected order IDs and new status
            selectedOrders.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = newStatus;
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }

        function openCourierModal() {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            const selectedOrders = Array.from(checkboxes).map(cb => cb.value);

            if (selectedOrders.length === 0) {
                alert('Please select at least one order');
                return;
            }

            const courier = document.getElementById('courierSelect').value;
            const courierName = courier === 'steadfast' ? 'Steadfast' : 'Pathao';

            if (!confirm(`Are you sure you want to send the selected orders to ${courierName}?`)) {
                return;
            }

            // Prepare form: set action based on courier
            const form = document.getElementById('courierForm');

            // Set the selected IDs
            document.getElementById('selectedIds').value = selectedOrders.join(',');

            // set action url based on courier selection
            const pathaoUrl = "{{ route('admin.pathao.create-bulk-orders') }}";
            const steadfastUrl = "{{ route('admin.steadfast.create-bulk-orders') }}";

            form.action = courier === 'steadfast' ? steadfastUrl : pathaoUrl;
            form.submit();
        }

        // Update button label when courier selection changes
        document.addEventListener('DOMContentLoaded', function() {
            const sel = document.getElementById('courierSelect');
            if (sel) {
                sel.addEventListener('change', updateCourierButton);
            }
        });
    </script>

    <script>
        document.querySelectorAll('.copy-order-id').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-order-id');
                const text = document.getElementById(id).innerText;
                navigator.clipboard.writeText(text).then(function() {
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-copy"></i>';
                    }, 1500);
                });
            });
        });

        // Initialize Select2 for bulk status select - Use jQuery when ready
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                $('#bulkStatusSelect').select2({
                    theme: "bootstrap",
                    width: '100%',
                    placeholder: "Change Status"
                });
            });
        }

        function openPaymentModal(orderId, orderHashedId, userId, userName, orderTotal, paidAmount) {
            currentOrderId = orderId;
            currentOrderHashedId = orderHashedId;
            currentUserId = userId;
            currentUserName = userName;
            currentOrderTotal = orderTotal;
            currentPaidAmount = paidAmount || 0;

            // Set form values
            document.getElementById('paymentOrderId').value = orderId;
            document.getElementById('paymentUserId').value = userId;
            document.getElementById('selectedUserName').textContent = userName;
            document.getElementById('selectedOrderId').textContent = 'Order #' + orderHashedId;

            // Calculate and display due amount
            const dueAmount = orderTotal - currentPaidAmount;
            document.getElementById('dueAmountDisplay').textContent = dueAmount.toFixed(2);

            // Set max value for amount input to prevent overpayment
            const amountInput = document.getElementById('paymentAmount');
            amountInput.setAttribute('max', dueAmount.toFixed(2));

            // Set current datetime
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const datetimeLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById('paymentCreatedAt').value = datetimeLocal;

            // Clear previous values
            document.getElementById('paymentTransactionId').value = '';
            document.getElementById('paymentAmount').value = '';
            document.getElementById('paymentMethod').value = '';

            // Show modal using jQuery
            if (typeof jQuery !== 'undefined') {
                jQuery('#paymentModal').modal('show');
            }
        }

        function insertPaymentMethod(method) {
            document.getElementById('paymentMethod').value = method;
        }

        function submitPayment() {
            const form = document.getElementById('paymentForm');

            // Validate required fields
            const amount = parseFloat(document.getElementById('paymentAmount').value);
            const method = document.getElementById('paymentMethod').value;
            const transactionId = document.getElementById('paymentTransactionId').value;

            if (!amount || amount <= 0) {
                alert('Please enter a valid amount');
                return;
            }

            // Check if amount exceeds due amount
            const dueAmount = currentOrderTotal - currentPaidAmount;
            if (amount > dueAmount) {
                alert('Payment amount cannot exceed due amount of ' + dueAmount.toFixed(2));
                return;
            }

            if (!method) {
                alert('Please enter a payment method');
                return;
            }

            // Transaction ID is now optional, so remove validation

            // Submit form
            form.submit();
        }
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2-bootstrap.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
@endsection
